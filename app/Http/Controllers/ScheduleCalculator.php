<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\CreditCalculations\Credit;
use App\Services\CreditCalculations\CreditCalculationsFactory;
use App\Services\CreditCalculations\Enum\CommissionType;
use App\Services\CreditCalculations\Enum\PeriodType;
use App\Services\CreditCalculations\Enum\TypeOfInstallment;
use App\Services\CreditCalculations\ScheduleFormatter;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ScheduleCalculator extends Controller
{
    public function __invoke(Request $request)
    {
        $credit = new Credit(
            $request->get('credit')['amountOfCredit'],
            $request->get('credit')['period'],
            PeriodType::getType($request->get('credit')['periodType']),
            $request->get('credit')['margin'],
            $request->get('credit')['wibor'],
            $request->get('credit')['commission'],
            CommissionType::getType($request->get('credit')['commissionType'])
        );

        $creditCalculation = CreditCalculationsFactory::createCreditCalculation(
            typeOfInstallment: TypeOfInstallment::getType($request->get('typeOfInstallment')),
            date: Carbon::create($request->get('date')['year'], $request->get('date')['month'] + 1),
            credit: $credit,
            interestsRateChanges: $request->get('interestsRateChange') ?? [],
            fixedFees: $request->get('fees')['fixed'] ?? [],
            changingFees: $request->get('fees')['changing'] ?? []
        );

        return response()->json([
            'schedule' => ScheduleFormatter::format($creditCalculation->schedule()->get())
        ]);
    }
}
