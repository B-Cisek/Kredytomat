<?php

namespace App\Services\CreditCalculations\Contract;

use App\Services\CreditCalculations\Credit;
use App\Services\CreditCalculations\Enum\TypeOfInstallment;
use Carbon\Carbon;

interface CreditCalculationsFactoryInterface
{
    public static function createCreditCalculation (
        TypeOfInstallment $typeOfInstallment,
        Carbon $date,
        Credit $credit,
        array $overpayments = [],
        array $interestsRateChanges = [],
        array $fixedFees = [],
        array $changingFees = []
    ): InstallmentsInterface;
}
