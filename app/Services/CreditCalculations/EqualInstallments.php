<?php

declare(strict_types=1);

namespace App\Services\CreditCalculations;

use App\Services\CreditCalculations\Contract\InstallmentsInterface;
use App\Services\CreditCalculations\Helper\CreditCalculationHelpers;
use App\Services\CreditCalculations\Helper\DateHelpers;
use Carbon\Carbon;

class EqualInstallments implements InstallmentsInterface
{
    use DateHelpers, CreditCalculationHelpers;

    private array $schedule = [];

    public function __construct(
        private readonly Carbon $date,
        private readonly Credit $credit,
        private readonly array  $overpayments = [],
        private readonly array  $interestsRateChanges = [],
        private readonly array  $fixedFees = [],
        private readonly array  $changingFees = [],
    )
    {
    }

    public function schedule(): static
    {
        $this->calculateInitialSchedule();
        $countNegative = $this->countNegativeValuesInColumn($this->schedule, 6);

        // if installment is too small, then adjust
        if ($this->schedule[array_key_last($this->schedule)][6] > 0) {
            do {
                $this->adjustInstallmentUp();
            } while ($this->schedule[array_key_last($this->schedule)][6] > 0);

            $this->fixLastRow();

            return $this;
        }

        if ($this->schedule[array_key_last($this->schedule)][6] < 0 &&
            $countNegative === 1 &&
            $this->schedule[0][5] > abs($this->schedule[array_key_last($this->schedule)][6])) {

            $this->fixLastRow();

            return $this;
        }

        // if installment is too large, then adjust
        if ($this->schedule[array_key_last($this->schedule)][6] < 0 &&
            ($countNegative > 1 || $this->schedule[0][5] < abs($this->schedule[array_key_last($this->schedule)][6]))) {

            do {
                $this->adjustInstallmentDown();
                $countNegative = $this->countNegativeValuesInColumn($this->schedule, 6);
            } while ($countNegative > 1 || ($this->schedule[0][5] < abs($this->schedule[array_key_last($this->schedule)][6])));

            $this->fixLastRow();

            return $this;
        }

        return $this;
    }

    private function adjustInstallmentUp(): void
    {
        foreach ($this->schedule as $key => &$row) {
            if ($key === 0) {
                $row[5] += 0.5;
                $row[4] = $this->getCapitalPart($row[5], $row[3]);
                $row[6] = $this->getCapitalAfterPay($row[2], $row[4]);
            } else {
                $lastRow = $this->schedule[$key - 1];
                $row[2] = $lastRow[6];
                $row[5] += 0.5;
                $row[4] = $this->getCapitalPart($row[5], $row[3]);
                $row[6] = $this->getCapitalAfterPay($row[2], $row[4]);
            }
        }
    }

    private function adjustInstallmentDown(): void
    {
        foreach ($this->schedule as $key => &$row) {
            if ($key === 0) {
                $row[5] -= 0.5;
                $row[4] = $this->getCapitalPart($row[5], $row[3]);
                $row[6] = $this->getCapitalAfterPay($row[2], $row[4]);
            } else {
                $lastRow = $this->schedule[$key - 1];
                $row[2] = $lastRow[6];
                $row[5] -= 0.5;
                $row[4] = $this->getCapitalPart($row[5], $row[3]);
                $row[6] = $this->getCapitalAfterPay($row[2], $row[4]);
            }
        }
    }

    private function fixLastRow(): void
    {
        $toPay = $this->schedule[array_key_last($this->schedule)][6];
        $this->schedule[array_key_last($this->schedule)][5] += $toPay;
        $this->schedule[array_key_last($this->schedule)][4] = $this->getCapitalPart(
            $this->schedule[array_key_last($this->schedule)][5],
            $this->schedule[array_key_last($this->schedule)][3]
        );
        $this->schedule[array_key_last($this->schedule)][6] = 0;
    }

    private function calculateInitialSchedule(): void
    {
        $this->setFirstScheduleRow();

        $currentDate = $this->getNextMonth($this->date);
        $this->schedule[0][] = $this->getFirstFixedFee($currentDate);
        $this->schedule[0][] = $this->getFirstChangingFee($currentDate, $this->credit->amountOfCredit);
        $this->schedule[0][] = 0; # overpayment

        for ($index = 1; $index < $this->credit->period * 12; $index++) {
            $lastRow = end($this->schedule);
            $currentDate = $this->getNextMonth($lastRow[0]);
            $numberOfDays = $currentDate->diffInDays($lastRow[0]);
            $capitalToPay = $lastRow[6];
            $isInterestRateChanged = false;
            $currentInterest = $lastRow[7];

            if ($this->getInterestsRateChanges($currentDate)) {
                $currentInterest = $this->getInterestsRateChanges($currentDate);
                $isInterestRateChanged = true;
            }

            if ($isInterestRateChanged) {
                $installment = $this->getInstallmentForEqualMonths($currentInterest);
            } else {
                $installment = $lastRow[5];
            }

            $interestPart = $this->getInterestPart(
                $currentInterest,
                $capitalToPay,
                $currentDate,
                $numberOfDays
            );

            $capitalPart = $this->getCapitalPart($installment, $interestPart);
            $capitalAfterPay = $this->getCapitalAfterPay($capitalToPay, $capitalPart);

            $fixedFee = $this->getFixedFees($currentDate);
            $changingFee = $this->getChangingFees($currentDate, $capitalToPay);

            $this->schedule[] = [
                $currentDate,
                $numberOfDays,
                $capitalToPay,
                $interestPart,
                $capitalPart,
                $installment,
                $capitalAfterPay,
                $currentInterest,
                $fixedFee,
                $changingFee,
                0 // overpayment
            ];

            $isInterestRateChanged = false;
        }
    }

    private function setFirstScheduleRow(): void
    {
        $currentDate = $this->getNextMonth($this->date);
        $numberOfDays = $currentDate->diffInDays($this->date);
        $currentInterest = $this->getCreditInterest($this->credit->margin, $this->credit->wibor);
        $installment = $this->getInstallmentForEqualMonths($currentInterest);
        $interestPart = $this->getInterestPart(
            $currentInterest,
            $this->credit->amountOfCredit,
            $currentDate,
            $numberOfDays
        );
        $capitalPart = $this->getCapitalPart($installment, $interestPart);
        $capitalAfterPay = $this->getCapitalAfterPay($this->credit->amountOfCredit, $capitalPart);

        $this->schedule[] = [
            $currentDate,
            $numberOfDays,
            $this->credit->amountOfCredit,
            $interestPart,
            $capitalPart,
            $installment,
            $capitalAfterPay,
            $currentInterest,
        ];
    }

    public function scheduleSmallerInstallment(): static
    {
        $this->setFirstScheduleRow();

        $currentDate = $this->getNextMonth($this->date);
        $this->schedule[0][] = $this->getFirstFixedFee($currentDate);
        $this->schedule[0][] = $this->getFirstChangingFee($currentDate, $this->credit->amountOfCredit);
        $this->schedule[0][] = $this->getFirstOverpayment($currentDate);

        for ($index = 1; $index < $this->credit->period * 12; $index++) {
            $lastRow = end($this->schedule);
            $currentDate = $this->getNextMonth($lastRow[0]);
            $numberOfDays = $currentDate->diffInDays($lastRow[0]);
            $capitalToPay = $lastRow[6] - $lastRow[10];
            $currentInterest = $lastRow[7];
            $interestPart = $this->getInterestPart($currentInterest, $capitalToPay, $currentDate, $numberOfDays);

            $installment = $lastRow[5];

            if ($lastRow[10] != 0) {
                $installment = $this->getInstallmentForEqualMonths($currentInterest, $capitalToPay, $index);
            }

            $capitalPart = $this->getCapitalPart($installment, $interestPart);
            $capitalAfterPay = $this->getCapitalAfterPay($capitalToPay, $capitalPart);

            $currentInterest = $this->getInterestsRateChanges($currentDate) ?? $currentInterest;
            $fixedFee = $this->getFixedFees($currentDate);
            $changingFee = $this->getChangingFees($currentDate, $capitalToPay);
            $overpayment = $this->getOverpayment($currentDate);

            $this->schedule[] = [
                $currentDate,
                $numberOfDays,
                $capitalToPay,
                $interestPart,
                $capitalPart,
                $installment,
                $capitalAfterPay,
                $currentInterest,
                $fixedFee,
                $changingFee,
                $overpayment
            ];

            if ($capitalAfterPay <= 0) {
                $last = $this->schedule[array_key_last($this->schedule)];
                $this->schedule[array_key_last($this->schedule)][5] = $last[3] + $last[2];
                $this->schedule[array_key_last($this->schedule)][4] = $this->getCapitalPart(
                    $this->schedule[array_key_last($this->schedule)][5],
                    $this->schedule[array_key_last($this->schedule)][3]
                );
                $this->schedule[array_key_last($this->schedule)][6] = 0;
                break;
            }
        }

        if ($this->schedule[array_key_last($this->schedule)][6] > 0) {
            $this->schedule[array_key_last($this->schedule)][5] += $this->schedule[array_key_last($this->schedule)][6];
            $this->schedule[array_key_last($this->schedule)][6] = 0;
        }

        return $this;
    }

    public function scheduleShorterPeriod(): static
    {
        $this->setFirstScheduleRow();

        $currentDate = $this->getNextMonth($this->date);
        $this->schedule[0][] = $this->getFirstFixedFee($currentDate);
        $this->schedule[0][] = $this->getFirstChangingFee($currentDate, $this->credit->amountOfCredit);
        $this->schedule[0][] = $this->getFirstOverpayment($currentDate);

        for ($index = 1; $index < $this->credit->period * 12; $index++) {
            $lastRow = end($this->schedule);
            $currentDate = $this->getNextMonth($lastRow[0]);
            $numberOfDays = $currentDate->diffInDays($lastRow[0]);
            $capitalToPay = $lastRow[6] - $lastRow[10];
            $currentInterest = $lastRow[7];
            $interestPart = $this->getInterestPart($currentInterest, $capitalToPay, $currentDate, $numberOfDays);
            $installment = $lastRow[5];

            $capitalPart = $this->getCapitalPart($installment, $interestPart);
            $capitalAfterPay = $this->getCapitalAfterPay($capitalToPay, $capitalPart);

            $currentInterest = $this->getInterestsRateChanges($currentDate) ?? $currentInterest;
            $fixedFee = $this->getFixedFees($currentDate);
            $changingFee = $this->getChangingFees($currentDate, $capitalToPay);
            $overpayment = $this->getOverpayment($currentDate);

            $this->schedule[] = [
                $currentDate,
                $numberOfDays,
                $capitalToPay,
                $interestPart,
                $capitalPart,
                $installment,
                $capitalAfterPay,
                $currentInterest,
                $fixedFee,
                $changingFee,
                $overpayment
            ];

            if ($capitalAfterPay <= 0) {
                $last = $this->schedule[array_key_last($this->schedule)];
                $this->schedule[array_key_last($this->schedule)][5] = $last[3] + $last[2];
                $this->schedule[array_key_last($this->schedule)][4] = $this->getCapitalPart(
                    $this->schedule[array_key_last($this->schedule)][5],
                    $this->schedule[array_key_last($this->schedule)][3]
                );
                $this->schedule[array_key_last($this->schedule)][6] = 0;
                break;
            }
        }

        return $this;
    }

    public function get(): array
    {
        return $this->schedule;
    }

    private function getFixedFees(Carbon $currentDate): float
    {
        foreach ($this->fixedFees as $value) {
            $startDate = Carbon::create($value['start']['year'], $value['start']['month'] + 1);
            $endDate = Carbon::create($value['end']['year'], $value['end']['month'] + 1);

            if ($this->isDateInRange($currentDate, $startDate, $endDate)) {
                return $value['fee'] ?? 0;
            }
        }

        return 0;
    }

    private function getChangingFees(Carbon $currentDate, float $capitalToPay): float
    {
        foreach ($this->changingFees as $value) {
            $startDate = Carbon::create($value['start']['year'], $value['start']['month'] + 1);
            $endDate = Carbon::create($value['end']['year'], $value['end']['month'] + 1);

            if ($this->isDateInRange($currentDate, $startDate, $endDate)) {
                if ($value['fee'] !== null) {
                    return $this->calculateChangingFee($this->toDecimal($value['fee']), $capitalToPay);
                }
            }
        }

        return 0;
    }

    private function getInterestsRateChanges(Carbon $currentDate): float|null
    {
        foreach ($this->interestsRateChanges as $value) {
            if (($value['date']['month'] + 1) === $currentDate->month && $value['date']['year'] === $currentDate->year) {
                if ($value['value'] !== null) {
                    return $this->toDecimal($value['value']);
                }
            }
        }

        return null;
    }

    private function getFirstFixedFee(Carbon $currentDate): float
    {
        foreach ($this->fixedFees as $value) {
            $startDate = Carbon::create($value['start']['year'], $value['start']['month'] + 1);
            $endDate = Carbon::create($value['end']['year'], $value['end']['month'] + 1);

            if ($this->isDateInRange($currentDate, $startDate, $endDate)) {
                return $value['fee'] ?? 0;
            }
        }

        return 0;
    }

    private function getFirstChangingFee(Carbon $currentDate, float $capitalToPay): float
    {
        foreach ($this->changingFees as $value) {
            $startDate = Carbon::create($value['start']['year'], $value['start']['month'] + 1);
            $endDate = Carbon::create($value['end']['year'], $value['end']['month'] + 1);

            if ($this->isDateInRange($currentDate, $startDate, $endDate)) {
                if ($value['fee'] !== null) {
                    return $this->calculateChangingFee($this->toDecimal($value['fee']), $capitalToPay);
                }
            }
        }

        return 0;
    }

    private function getCreditInterest(float $margin, float $wibor): float
    {
        return $this->toDecimal($margin + $wibor);
    }

    private function getInstallmentForEqualMonths(
        float $currentInterest,
        ?float $amountOfCredit = null,
        ?int $index = null): float
    {
        $mn = $this->credit->period * 12;

        if ($index) {
            $mn -= $index;
        }

        $interestRate = $currentInterest / 12;

        $amount = $amountOfCredit ?? $this->credit->amountOfCredit;

        return $amount * $interestRate * ($interestRate + 1) ** $mn / ((($interestRate + 1) ** $mn) - 1);
    }

    private function getInterestPart(float $interest, float $capitalToPay, Carbon $date, int $numberOfDays): float
    {
        $dailyInterestRate = $this->getDailyInterestRate($date, $interest);

        return $capitalToPay * $dailyInterestRate * $numberOfDays;
    }

    private function getCapitalPart(float $installment, float $interestPart): float
    {
        if ($interestPart < 0) {
            return $installment;
        }

        return $installment - $interestPart;
    }

    private function getCapitalAfterPay(float $amountOfCredit, float $capitalPart): float
    {
        return $amountOfCredit - $capitalPart;
    }

    private function getInstallment(float $currentInterest): float
    {
        $daysAvg = $this->getAvgCreditDays($this->date, $this->credit->period);

        $r30 = $currentInterest / 12;

        $interest = ($r30 * $daysAvg) / 30;

        $mn = $this->credit->period * 12;

        return ($this->credit->amountOfCredit * $interest * ($interest + 1) ** $mn) / (($interest + 1) ** $mn - 1);
    }

    private function getFirstOverpayment(Carbon $currentDate): float
    {
        foreach ($this->overpayments as $value) {
            $startDate = Carbon::create($value['start']['year'], $value['start']['month'] + 1);
            $endDate = Carbon::create($value['end']['year'], $value['end']['month'] + 1);

            if ($this->isDateInRange($currentDate, $startDate, $endDate)) {
                return $value['overpayment'] ?? 0;
            }
        }

        return 0;
    }

    private function getOverpayment(Carbon $currentDate): float
    {
        foreach ($this->overpayments as $value) {
            $startDate = Carbon::create($value['start']['year'], $value['start']['month'] + 1);
            $endDate = Carbon::create($value['end']['year'], $value['end']['month'] + 1);

            if ($this->isDateInRange($currentDate, $startDate, $endDate)) {
                return $value['overpayment'] ?? 0;
            }
        }

        return 0;
    }
}
