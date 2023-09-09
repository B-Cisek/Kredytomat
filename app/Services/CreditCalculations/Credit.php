<?php

declare(strict_types=1);

namespace App\Services\CreditCalculations;

use App\Services\CreditCalculations\Enum\CommissionType;
use App\Services\CreditCalculations\Enum\PeriodType;

/**
 * @property int $amountOfCredit
 * @property $period
 * @property $periodType
 * @property $margin
 * @property $commission
 * @property $commissionType
 * @property $wibor
 */
class Credit
{
    public function __construct(
        public readonly int $amountOfCredit,
        public readonly int $period,
        public readonly PeriodType $periodType,
        public readonly float $margin,
        public readonly float $wibor,
        public readonly float $commission,
        public readonly CommissionType $commissionType,
    ){}
}
