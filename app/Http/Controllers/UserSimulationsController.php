<?php

namespace App\Http\Controllers;

use App\Models\CreditSimulation;
use App\Models\OverpaymentSimulation;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class UserSimulationsController extends Controller
{
    public function __invoke(): Response
    {
        $userId = Auth::user()->id;
        $creditSimulationCount = CreditSimulation::where('user_id', $userId)
            ->get()
            ->count();
        $overpaymentSimulationCount = OverpaymentSimulation::where('user_id', $userId)
            ->get()
            ->count();

        return Inertia::render('UserSimulations',
            compact(
                'creditSimulationCount',
                'overpaymentSimulationCount'
            ));
    }
}
