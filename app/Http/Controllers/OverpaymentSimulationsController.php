<?php

namespace App\Http\Controllers;

use App\Enums\AlertType;
use App\Models\OverpaymentSimulation;
use App\Models\Wibor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class OverpaymentSimulationsController extends Controller
{
    public function index(): Response
    {
        $overpaymentSimulation = OverpaymentSimulation::with('wibor')->paginate(10);

        return Inertia::render('OverpaymentSimulations', compact('overpaymentSimulation'));
    }

    public function show($overpaymentSimulation): Response
    {
        $overpaymentSimulation = OverpaymentSimulation::with('wibor')->findOrFail($overpaymentSimulation);

        return Inertia::render('OverpaymentSimulation', compact('overpaymentSimulation'));
    }

    public function save(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'amount_of_credit' => 'required|numeric',
            'period' => 'required|numeric',
            'margin' => 'required|numeric',
            'commission' => 'required|numeric',
            'type_of_installment' => 'required|string',
            'overpayment_type' => 'string|required',
            'wibor_id' => 'required|numeric',
            'overpayments' => 'json'
        ]);

        $wibor = Wibor::where('value', $validated['wibor_id'])->first();

        $validated['wibor_id'] = $wibor->id;
        $validated['user_id'] = Auth::id();

        $overpaymentsSimulation = OverpaymentSimulation::where('amount_of_credit', $validated['amount_of_credit'])
            ->where('period', $validated['period'])
            ->where('margin', $validated['margin'])
            ->where('commission', $validated['commission'])
            ->where('type_of_installment', $validated['type_of_installment'])
            ->where('wibor_id', $validated['wibor_id'])
            ->where('overpayments', $validated['overpayments'])
            ->first();

        if (!is_null($overpaymentsSimulation)) {
            return back()->with([
                'alert_type' => AlertType::WARNING,
                'alert_message' => 'Symulacja nadpłaty już istnieje!'
            ]);
        }

        OverpaymentSimulation::create($validated);

        return back()->with([
            'alert_type' => AlertType::SUCCESS,
            'alert_message' => 'Zapisano symulacje nadpłaty!'
        ]);
    }
}
