<?php

declare(strict_types=1);

namespace App\Services;

class CommissionCalculatorService
{
    public static function calculate(int|float $commission, int|float $amountOfCredit): float
    {
        return round(($commission / $amountOfCredit) * 100, 2);
    }
}
