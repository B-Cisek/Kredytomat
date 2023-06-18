<?php

namespace App\Http\Controllers;

use App\Models\Wibor;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class OverpaymentCalculatorController extends Controller
{
    public function __invoke(Request $request): Response
    {
        $data = [
            'amount_of_credit' => $request->input('amount_of_credit'),
            'period' => $request->input('period'),
            'margin' => $request->input('margin'),
            'commission' => $request->input('commission'),
            'commission_type' => 'percent',
            'wibor' => $request->input('wibor'),
            'type_of_installment' => $request->input('type_of_installment'),
            'overpayment_type' => $request->input('overpayment_type'),
            'overpayments' => $request->input('overpayments')
        ];

        return Inertia::render('CreditOverpaymentView', [
            'wiborList' => Wibor::all(),
            'defaultData' => $data
        ]);
    }
}
