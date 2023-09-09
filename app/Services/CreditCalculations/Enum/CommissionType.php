<?php

namespace App\Services\CreditCalculations\Enum;

enum CommissionType: string
{
    case PERCENT = 'percent';
    case NUMBER = 'number';

    public static function getType(string $name): self
    {
        return match ($name) {
            self::PERCENT->value => self::PERCENT,
            self::NUMBER->value => self::NUMBER,
        };
    }
}
