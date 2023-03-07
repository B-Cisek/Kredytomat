<?php

namespace App\Http\Controllers;

use App\Models\OverpaymentSimulation;
use Inertia\Inertia;
use Inertia\Response;

class OverpaymentSimulationsController extends Controller
{
    public function index(): Response
    {
        $overpaymentSimulation = OverpaymentSimulation::all();

        return Inertia::render('OverpaymentSimulations', [
            'overpaymentSimulation' => $overpaymentSimulation
        ]);
    }
}
