<?php

declare(strict_types=1);

namespace App\Services\CreditCalculations\Helper;

use Carbon\Carbon;

trait CreditCalculationHelpers
{
    protected function toDecimal(float $percent): float
    {
        return $percent / 100;
    }

    /**
     * @param float $fee fee percent in decimal
     */
    protected function calculateChangingFee(float $fee, float $capitalToPay): float
    {
        return ($capitalToPay * $fee) / 100;
    }

    protected function getDailyInterestRate(Carbon $date, float $interestRate): float
    {
        return $date->isLeapYear()
            ? $interestRate / 366
            : $interestRate / 365;
    }

    protected function countNegativeValuesInColumn(array $schedule, int $columnNumber): int
    {
        $countNegative = 0;

        foreach ($schedule as $row) {
            if ($row[$columnNumber] < 0) {
                $countNegative++;
            }
        }

        return $countNegative;
    }
}
