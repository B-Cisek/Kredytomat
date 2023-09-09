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

    protected function calculateChangingFee(float $fee, float $capitalToPay): float
    {
        $capitalToPay = $capitalToPay / 100;

        $result = ($capitalToPay * $fee) / 100;

        return $result * 100;
    }

    protected function getDailyInterestRate(Carbon $date, float $interestRate): float
    {
        return $date->isLeapYear()
            ? $interestRate / 366
            : $interestRate / 365;
    }
}
