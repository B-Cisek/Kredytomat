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

    private array $schedule;

    public function __construct(
        private readonly Carbon $date,
        private readonly Credit $credit,
        private readonly array $overpayments = [],
        private readonly array $interestsRateChanges = [],
        private readonly array $fixedFees = [],
        private readonly array $changingFees = [],
    ){}

    public function schedule(): static
    {
        $this->calculateInitialSchedule();

        $schedule = $this->schedule;

        if ($schedule[array_key_last($schedule)][6] < 0 && $schedule[array_key_last($schedule)][6] > -200) {
            $toPay = $this->schedule[array_key_last($this->schedule)][6];
            $this->schedule[array_key_last($this->schedule)][5] += $toPay;
            $this->schedule[array_key_last($this->schedule)][4] = $this->getCapitalPart(
                $this->schedule[array_key_last($this->schedule)][5],
                $this->schedule[array_key_last($this->schedule)][3]
            );
            $this->schedule[array_key_last($this->schedule)][6] = 0;
        }

        if ($schedule[array_key_last($schedule)][6] > 0) {
            do {
                $newInstallment = $schedule[array_key_last($schedule)][5] + 0.5;
                $this->calculateInitialSchedule($newInstallment);
            } while (! ($this->schedule[array_key_last($this->schedule)][6] <= 0));


            $toPay = $this->schedule[array_key_last($this->schedule)][6];
            $this->schedule[array_key_last($this->schedule)][5] += $toPay;
            $this->schedule[array_key_last($this->schedule)][4] = $this->getCapitalPart(
                $this->schedule[array_key_last($this->schedule)][5],
                $this->schedule[array_key_last($this->schedule)][3]
            );
            $this->schedule[array_key_last($this->schedule)][6] = 0;
        }


        return $this;
    }

    private function  calculateInitialSchedule(?float $givenInstallment = null): void
    {
        $this->schedule = [];
        $this->setFirstScheduleRow($givenInstallment);

        $currentDate = $this->getNextMonth($this->date);
        $this->schedule[0][] = $this->getFirstFixedFee($currentDate);
        $this->schedule[0][] = $this->getFirstChangingFee($currentDate, $this->credit->amountOfCredit);
        $this->schedule[0][] = 0; # overpayment

        for ($index = 1; $index < $this->credit->period * 12; $index++) {
            $lastRow = end($this->schedule);
            $currentDate = $this->getNextMonth($lastRow[0]);
            $numberOfDays = $currentDate->diffInDays($lastRow[0]);
            $capitalToPay =  $lastRow[6];
            $currentInterest = $lastRow[7];

            $interestPart = $this->getInterestPart(
                $currentInterest,
                $capitalToPay,
                $currentDate,
                $numberOfDays
            );
            $installment =  $givenInstallment ?? $lastRow[5]; //$this->getInstallmentForEqualMonths($currentInterest);

            $capitalPart = $this->getCapitalPart($installment, $interestPart);
            $capitalAfterPay = $this->getCapitalAfterPay($capitalToPay, $capitalPart);

            $currentInterest = $this->getInterestsRateChanges($currentDate) ?? $currentInterest;
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
    }

    private function setFirstScheduleRow(?float $givenInstallment = null): void
    {
        $currentDate = $this->getNextMonth($this->date);
        $numberOfDays = $currentDate->diffInDays($this->date);
        $currentInterest = $this->getCreditInterest($this->credit->margin, $this->credit->wibor);
        $installment = $givenInstallment ?? $this->getInstallmentForEqualMonths($currentInterest);
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


    public function scheduleShorterPeriod(): static
    {
        // TODO: Implement scheduleShorterPeriod() method.
    }

    public function scheduleSmallerInstallment(): static
    {
        // TODO: Implement scheduleSmallerInstallment() method.
    }

    public function get(): array
    {
        return $this->schedule;
    }

    private function getFixedFees(Carbon $currentDate): float
    {
        foreach ($this->fixedFees as $value) {
            $startDate = Carbon::create($value['date']['start']['year'], $value['date']['start']['month']);
            $endDate = Carbon::create($value['date']['end']['year'], $value['date']['end']['month']);

            if ($this->isDateInRange($currentDate, $startDate, $endDate)) {
                return $value['value'];
            }
        }

        return 0;
    }

    private function getChangingFees(Carbon $currentDate, float $capitalToPay): float
    {
        foreach ($this->changingFees as $value) {
            $startDate = Carbon::create($value['start']['year'], $value['start']['month']);
            $endDate = Carbon::create($value['end']['year'], $value['end']['month']);

            if ($this->isDateInRange($currentDate, $startDate, $endDate)) {
                return $this->calculateChangingFee($this->toDecimal($value['fee']), $capitalToPay);
            }
        }

        return 0;
    }

    private function getInterestsRateChanges(Carbon $currentDate): float|null
    {
        foreach ($this->interestsRateChanges as $value) {
            if ($value['date']['month'] === $currentDate->month && $value['date']['year'] === $currentDate->year) {
                return $this->toDecimal($value['value']);
            }
        }

        return null;
    }

    private function getFirstFixedFee(Carbon $currentDate): float
    {
        foreach ($this->fixedFees as $value) {
            $startDate = Carbon::create($value['date']['start']['year'], $value['date']['start']['month']);
            $endDate = Carbon::create($value['date']['end']['year'], $value['date']['end']['month']);

            if ($this->isDateInRange($currentDate, $startDate, $endDate)) {
                return $value['value'];
            }
        }

        return 0;
    }

    private function getFirstChangingFee(Carbon $currentDate, float $capitalToPay): float
    {
        foreach ($this->changingFees as $value) {
            $startDate = Carbon::create($value['start']['year'], $value['start']['month']);
            $endDate = Carbon::create($value['end']['year'], $value['end']['month']);

            if ($this->isDateInRange($currentDate, $startDate, $endDate)) {
                return $this->calculateChangingFee($this->toDecimal($value['fee']), $capitalToPay);
            }
        }

        return 0;
    }

    private function getCreditInterest(float $margin, float $wibor): float
    {
        return $this->toDecimal($margin + $wibor);
    }

    private function getInstallmentForEqualMonths(float $currentInterest): float
    {
        $mn = $this->credit->period * 12;

        $interestRate = $currentInterest / 12;

        return $this->credit->amountOfCredit * $interestRate * ($interestRate + 1) ** $mn / ((($interestRate + 1) ** $mn) - 1);
    }

    private function getInterestPart(float $interest, float $capitalToPay, Carbon $date, int $numberOfDays): float
    {
        $dailyInterestRate = $this->getDailyInterestRate($date , $interest); // $dailyInterestRate are float

        return $capitalToPay * $dailyInterestRate * $numberOfDays;
    }

    private function getCapitalPart(float $installment, float $interestPart): float
    {
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

        return ($this->credit->amountOfCredit * $interest * ($interest + 1) ** $mn) / (($interest + 1) ** $mn -1);
    }
}
