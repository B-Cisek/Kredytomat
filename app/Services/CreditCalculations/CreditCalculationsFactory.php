<?php

declare(strict_types=1);

namespace App\Services\CreditCalculations;

use App\Services\CreditCalculations\Contract\CreditCalculationsFactoryInterface;
use App\Services\CreditCalculations\Contract\InstallmentsInterface;
use App\Services\CreditCalculations\Enum\TypeOfInstallment;
use Carbon\Carbon;

class CreditCalculationsFactory implements CreditCalculationsFactoryInterface
{
    public static function createCreditCalculation(
        TypeOfInstallment $typeOfInstallment,
        Carbon $date,
        Credit $credit,
        array $overpayments = [],
        array $interestsRateChanges = [],
        array $fixedFees = [],
        array $changingFees = []
    ): InstallmentsInterface
    {
        switch ($typeOfInstallment) {
            case TypeOfInstallment::EQUAL:
                return new EqualInstallments($date, $credit, $overpayments, $interestsRateChanges, $fixedFees, $changingFees);
            case TypeOfInstallment::DECREASING:
                return new DecreasingInstallments($date, $credit, $overpayments, $interestsRateChanges, $fixedFees, $changingFees);
        }
    }
}
