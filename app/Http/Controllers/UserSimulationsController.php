<?php

namespace App\Http\Controllers;

use App\Models\CreditSimulation;
use App\Models\OverpaymentSimulation;
use Inertia\Inertia;
use Inertia\Response;

class UserSimulationsController extends Controller
{
    public function __invoke(): Response
    {
        return Inertia::render('UserSimulations', [
            'creditSimulationCount' => CreditSimulation::all()->count(),
            'overpaymentSimulationCount' => OverpaymentSimulation::all()->count(),
        ]);
    }
}
