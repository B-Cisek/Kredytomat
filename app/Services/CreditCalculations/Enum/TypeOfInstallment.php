<?php

namespace App\Services\CreditCalculations\Enum;

enum TypeOfInstallment: string
{
    case EQUAL = 'equal';
    case DECREASING = 'decreasing';

    public static function getType(string $name): self
    {
        return match ($name) {
            self::EQUAL->value => self::EQUAL,
            self::DECREASING->value => self::DECREASING,
        };
    }
}
