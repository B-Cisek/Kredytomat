<?php

namespace App\Http\Controllers;

use App\Models\Wibor;
use Inertia\Inertia;
use Inertia\Response;

class OverpaymentCalculatorController extends Controller
{
    public function __invoke(): Response
    {
        return Inertia::render('CreditOverpaymentView', [
            'wiborList' => Wibor::all()
        ]);
    }
}
