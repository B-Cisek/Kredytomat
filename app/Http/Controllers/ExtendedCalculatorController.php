<?php

namespace App\Http\Controllers;

use App\Models\Wibor;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ExtendedCalculatorController extends Controller
{
    public function __invoke(Request $request): Response
    {
        $data = [
            'amountOfCredit' => $request->input('amount_of_credit'),
            'period' => $request->input('period'),
            'margin' => $request->input('margin'),
            'commission' => $request->input('commission'),
            'wibor' => $request->input('wibor'),
            'typeOfInstallment' => $request->input('type_of_installment'),
            'changingFees' => $request->input('changing_fees'),
            'fixedFees' => $request->input('fixed_fees')
        ];

        return Inertia::render('CalculatorExtended', [
            'wiborList' => Wibor::all(),
            'defaultData' => $data
        ]);
    }
}
