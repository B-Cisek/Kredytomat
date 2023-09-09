<?php

namespace App\Services\CreditCalculations\Enum;

enum OverpaymentType: string
{
    case INSTALLMENT = 'installment';
    case PERIOD = 'period';

    public static function getType(string $name): self
    {
        return match ($name) {
            self::INSTALLMENT->value => self::INSTALLMENT,
            self::PERIOD->value => self::PERIOD,
        };
    }
}
