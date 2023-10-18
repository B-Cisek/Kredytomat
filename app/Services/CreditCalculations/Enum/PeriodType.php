<?php

namespace App\Services\CreditCalculations\Enum;

enum PeriodType: string
{
    case YEAR = 'year';
    case MONTH = 'month';

    public static function getType(string $name): self
    {
        return match ($name) {
            self::YEAR->value => self::YEAR,
            self::MONTH->value => self::MONTH,
        };
    }
}
