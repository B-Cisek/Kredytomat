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
        $currentDate = $this->getNextMonth($this->date);
        $numberOfDays = $currentDate->diffInDays($this->date);
        $currentInterest = $this->getCreditInterest($this->credit->margin, $this->credit->wibor);
        $interestPart = $this->getInterestPart(
            $currentInterest,
            $this->credit->amountOfCredit,
            $currentDate,
            $numberOfDays
        );
        $installment = $this->getInstallmentForEqualMonths($currentInterest);
        $capitalPart = $this->getCapitalPart($installment, $interestPart);
        $capitalAfterPay = $this->getCapitalAfterPay($this->credit->amountOfCredit, $capitalPart);


        $this->schedule[] = [
            $currentDate,
            $numberOfDays,
            $this->credit->amountOfCredit,
            $interestPart,
            $capitalPart,
            $installment,
            $capitalAfterPay
        ];

        for ($index = 1; $index < $this->credit->period * 12; $index++) {
            $lastRow = end($this->schedule);
            $currentDate = $this->getNextMonth($lastRow[0]);
            $numberOfDays = $currentDate->diffInDays($lastRow[0]);
            $capitalToPay =  $lastRow[6];
            // $currentInterest = $lastRow[7];

            $currentInterest = $this->getCreditInterest($this->credit->margin, $this->credit->wibor);

            $interestPart = $this->getInterestPart(
                $currentInterest,
                $capitalToPay,
                $currentDate,
                $numberOfDays
            );


            $installment = $this->getInstallmentForEqualMonths($currentInterest);
            $capitalPart = $this->getCapitalPart($installment, $interestPart);
            $capitalAfterPay = $this->getCapitalAfterPay($capitalToPay, $capitalPart);

            $this->schedule[] = [
                $currentDate,
                $numberOfDays,
                $capitalToPay,
                $interestPart,
                $capitalPart,
                $installment,
                $capitalAfterPay,
            ];

        }

        if ($this->schedule[array_key_last($this->schedule)][6] > 0) {
            do {
                $installmentTotal = $this->schedule[array_key_last($this->schedule)][5];
                $installmentTotal += 1;
                $this->schedule = [];

                $currentDate = $this->getNextMonth($this->date);
                $numberOfDays = $currentDate->diffInDays($this->date);
                $currentInterest = $this->getCreditInterest($this->credit->margin, $this->credit->wibor);
                $interestPart = $this->getInterestPart(
                    $currentInterest,
                    $this->credit->amountOfCredit,
                    $currentDate,
                    $numberOfDays
                );
                $installment = $installmentTotal;
                $capitalPart = $this->getCapitalPart($installment, $interestPart);
                $capitalAfterPay = $this->getCapitalAfterPay($this->credit->amountOfCredit, $capitalPart);


                $this->schedule[] = [
                    $currentDate,
                    $numberOfDays,
                    $this->credit->amountOfCredit,
                    $interestPart,
                    $capitalPart,
                    $installment,
                    $capitalAfterPay
                ];

                for ($index = 1; $index < $this->credit->period * 12; $index++) {
                    $lastRow = end($this->schedule);
                    $currentDate = $this->getNextMonth($lastRow[0]);
                    $numberOfDays = $currentDate->diffInDays($lastRow[0]);
                    $capitalToPay =  $lastRow[6];
                    // $currentInterest = $lastRow[7];

                    $currentInterest = $this->getCreditInterest($this->credit->margin, $this->credit->wibor);

                    $interestPart = $this->getInterestPart(
                        $currentInterest,
                        $capitalToPay,
                        $currentDate,
                        $numberOfDays
                    );

                    $installment = $installmentTotal;
                    $capitalPart = $this->getCapitalPart($installment, $interestPart);
                    $capitalAfterPay = $this->getCapitalAfterPay($capitalToPay, $capitalPart);

                    $this->schedule[] = [
                        $currentDate,
                        $numberOfDays,
                        $capitalToPay,
                        $interestPart,
                        $capitalPart,
                        $installment,
                        $capitalAfterPay,
                    ];

                }


            } while (!($this->schedule[array_key_last($this->schedule)][6] <= 0)
            || !($this->schedule[array_key_last($this->schedule)][6] < -5000));
        }

        $toPay = $this->schedule[array_key_last($this->schedule)][6];
        $this->schedule[array_key_last($this->schedule)][5] +=  $toPay;

        $this->schedule[array_key_last($this->schedule)][4] = $this->getCapitalPart(
            $this->schedule[array_key_last($this->schedule)][5],
            $this->schedule[array_key_last($this->schedule)][3]
        );

       $this->schedule[array_key_last($this->schedule)][6] = 0;

        return $this;
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

    private function getCreditInterest(float $margin, float $wibor): float
    {
        return $this->toDecimal($margin + $wibor);
    }

    private function getInstallmentForEqualMonths(float $currentInterest): int
    {
        $mn = $this->credit->period * 12;

        $interestRate = $currentInterest / 12;

        return (int) ($this->credit->amountOfCredit * $interestRate * ($interestRate + 1) ** $mn / ((($interestRate + 1) ** $mn) - 1));
    }

    private function getInterestPart(float $interest, int $capitalToPay, Carbon $date, int $numberOfDays): int
    {
        $dailyInterestRate = $this->getDailyInterestRate($date , $interest); // $dailyInterestRate are float

        return (int) ($capitalToPay * $dailyInterestRate * $numberOfDays);
    }

    private function getCapitalPart(int $installment, int $interestPart): int
    {
        return $installment - $interestPart;
    }

    private function getCapitalAfterPay(int $amountOfCredit, int $capitalPart): int
    {
        return $amountOfCredit - $capitalPart;
    }

    private function getInstallment(float $currentInterest): int
    {
        $daysAvg = $this->getAvgCreditDays($this->date, $this->credit->period);

        $r30 = $currentInterest / 12;

        $interest = ($r30 * $daysAvg) / 30;

        $mn = $this->credit->period * 12;

        return (int) (($this->credit->amountOfCredit * $interest * ($interest + 1) ** $mn) / (($interest + 1) ** $mn -1));
    }
}
