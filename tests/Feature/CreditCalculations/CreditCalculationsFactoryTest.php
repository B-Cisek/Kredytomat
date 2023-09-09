<?php

namespace CreditCalculations;

use App\Services\CreditCalculations\Contract\InstallmentsInterface;
use App\Services\CreditCalculations\Credit;
use App\Services\CreditCalculations\CreditCalculationsFactory;
use App\Services\CreditCalculations\DecreasingInstallments;
use App\Services\CreditCalculations\Enum\CommissionType;
use App\Services\CreditCalculations\Enum\PeriodType;
use App\Services\CreditCalculations\Enum\TypeOfInstallment;
use Carbon\Carbon;
use Tests\TestCase;

class CreditCalculationsFactoryTest extends TestCase
{
    public function test_factory_return_proper_object(): void
    {
        $credit = new Credit(
            300_000,
            20,
            PeriodType::YEAR,
            2.3,
            6.44,
            0,
            CommissionType::NUMBER
        );

        $creditCalculationDecreasing = CreditCalculationsFactory::createCreditCalculation(
            TypeOfInstallment::DECREASING,
            Carbon::create(2023,8),
            $credit,
        );


        $this->assertEquals($creditCalculationDecreasing , new DecreasingInstallments(
            Carbon::create(2023,8),
            $credit
        ));

        $this->assertInstanceOf(InstallmentsInterface::class, $creditCalculationDecreasing);


//        $creditCalculationEqual = CreditCalculationsFactory::createCreditCalculation(
//            TypeOfInstallment::EQUAL,
//            Carbon::create(2023,8),
//            $credit,
//        );

//        $this->assertObjectEquals($creditCalculationEqual , new EqualInstallments(
//            Carbon::create(2023,8),
//            $credit
//        )); TODO:
    }
}
