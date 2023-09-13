<?php

declare(strict_types=1);

namespace App\Services\CreditCalculations;

use App\Services\CreditCalculations\Contract\InstallmentsInterface;
use App\Services\CreditCalculations\Helper\CreditCalculationHelpers;
use App\Services\CreditCalculations\Helper\DateHelpers;
use Carbon\Carbon;

class DecreasingInstallments implements InstallmentsInterface
{
    use CreditCalculationHelpers, DateHelpers;

    private array $schedule;

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
            $currentInterest = $this->getInterestsRateChanges($currentDate) ?? $lastRow[7];
            $capitalPart = $this->getCapitalPart($this->credit->amountOfCredit, $this->credit->period);
            $interestPart = $this->getInterestPart(
                $currentInterest,
                $capitalToPay,
                $currentDate,
                $numberOfDays
            );
            $installment = $this->getInstallment($interestPart, $capitalPart);
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
            $capitalPart = $this->getCapitalPart($this->credit->amountOfCredit, $this->credit->period);
            $interestPart = $this->getInterestPart(
                $currentInterest,
                $capitalToPay,
                $currentDate,
                $numberOfDays
            );
            $installment = $this->getInstallment($interestPart, $capitalPart);
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
                $this->schedule[array_key_last($this->schedule)][4] = $last[4] + $last[6];
                $this->schedule[array_key_last($this->schedule)][6] = 0;
                break;
            };
        }

        return $this;
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
            $capitalPart = $lastRow[4] === 0
                ? $lastRow[4]
                : $this->getNewCapitalPart($capitalToPay, $index);
            $interestPart = $this->getInterestPart(
                $currentInterest,
                $capitalToPay,
                $currentDate,
                $numberOfDays
            );
            $installment = $this->getInstallment($interestPart, $capitalPart);
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

        }

        return $this;
    }

    public function get(): array
    {
        return $this->schedule;
    }

    private function getInterestsRateChanges(Carbon $currentDate): float|null
    {
        foreach ($this->interestsRateChanges as $value) {
            if (($value['date']['month'] + 1) === $currentDate->month
                && $value['date']['year'] === $currentDate->year) {
                return $this->toDecimal($value['value']);
            }
        }

        return null;
    }

    private function getFixedFees(Carbon $currentDate): float
    {
        foreach ($this->fixedFees as $value) {
            $startDate = Carbon::create($value['start']['year'], $value['start']['month'] + 1);
            $endDate = Carbon::create($value['end']['year'], $value['end']['month'] + 1);

            if ($this->isDateInRange($currentDate, $startDate, $endDate)) {
                return $value['fee'];
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
                return $this->calculateChangingFee($value['fee'], $capitalToPay);
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
                return $value['overpayment'];
            }
        }

        return 0;
    }

    private function setFirstScheduleRow(): void
    {
        $currentDate = $this->getNextMonth($this->date);
        $numberOfDays = $currentDate->diffInDays($this->date);
        $currentInterest = $this->getCreditInterest($this->credit->margin, $this->credit->wibor);
        $capitalPart = $this->getCapitalPart($this->credit->amountOfCredit, $this->credit->period);
        $interestPart = $this->getInterestPart(
            $currentInterest,
            $this->credit->amountOfCredit,
            $currentDate,
            $numberOfDays
        );
        $installment = $this->getInstallment($interestPart, $capitalPart);
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

    private function getInterestPart(float $interest, float $capitalToPay, Carbon $date, int $numberOfDays): float
    {
        $dailyInterestRate = $this->getDailyInterestRate($date, $interest);

        return $capitalToPay * $dailyInterestRate * $numberOfDays;
    }

    private function getCreditInterest(float $margin, float $wibor): float
    {
        return $this->toDecimal($margin + $wibor);
    }

    private function getCapitalPart(float $amountOfCredit, float $period): float
    {
        $mn = $period * 12;

        return $amountOfCredit / $mn;
    }

    private function getInstallment(float $interestPart, float $capitalPart): float
    {
        return $interestPart + $capitalPart;
    }

    private function getCapitalAfterPay(float $amountOfCredit, float $capitalPart): float
    {
        return $amountOfCredit - $capitalPart;
    }

    private function getNewCapitalPart(float $capitalToPay, int $n): float
    {
        $mn = $this->credit->period * 12;

        return $capitalToPay / ($mn - $n);
    }

    private function getFirstFixedFee(Carbon $currentDate): float
    {
        foreach ($this->fixedFees as $value) {
            $startDate = Carbon::create($value['start']['year'], $value['start']['month'] + 1);
            $endDate = Carbon::create($value['end']['year'], $value['end']['month'] + 1);

            if ($this->isDateInRange($currentDate, $startDate, $endDate)) {
                return $value['fee'];
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
                return $this->calculateChangingFee($value['fee'], $capitalToPay);
            }
        }

        return 0;
    }

    private function getFirstOverpayment(Carbon $currentDate): float
    {
        foreach ($this->overpayments as $value) {
            $startDate = Carbon::create($value['start']['year'], $value['start']['month'] + 1);
            $endDate = Carbon::create($value['end']['year'], $value['end']['month'] + 1);

            if ($this->isDateInRange($currentDate, $startDate, $endDate)) {
                return $value['overpayment'];
            }
        }

        return 0;
    }
}
