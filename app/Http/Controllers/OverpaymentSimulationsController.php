<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\AlertType;
use App\Http\Requests\OverpaymentSimulationRequest;
use App\Models\OverpaymentSimulation;
use App\Models\Wibor;
use App\Services\CommissionCalculatorService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class OverpaymentSimulationsController extends Controller
{
    public function index(): Response
    {
        $userId = Auth::user()->id;

        $overpaymentSimulation = OverpaymentSimulation::with('wibor')
            ->where('user_id', $userId)
            ->paginate(10);

        return Inertia::render(
            'OverpaymentSimulations',
            compact('overpaymentSimulation'));
    }

    public function show($overpaymentSimulation): Response
    {
        $overpaymentSimulation = OverpaymentSimulation::with('wibor')->findOrFail($overpaymentSimulation);

        return Inertia::render('OverpaymentSimulation', compact('overpaymentSimulation'));
    }

    public function save(OverpaymentSimulationRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        if ($validated['commission_type'] === 'number') {
            $validated['commission'] = CommissionCalculatorService::calculate(
                $validated['commission'],
                $validated['amount_of_credit']
            );
        }

        unset($validated['commission_type']);

        $wibor = Wibor::where('value', $validated['wibor_id'])->first();

        $validated['wibor_id'] = $wibor->id;
        $validated['user_id'] = Auth::id();

        $overpaymentsSimulation = OverpaymentSimulation::where('amount_of_credit', $validated['amount_of_credit'])
            ->where('period', $validated['period'])
            ->where('user_id', $validated['user_id'])
            ->where('start_date', $validated['start_date'])
            ->where('margin', $validated['margin'])
            ->where('commission', $validated['commission'])
            ->where('type_of_installment', $validated['type_of_installment'])
            ->where('wibor_id', $validated['wibor_id'])
            ->where('overpayment_type', $validated['overpayment_type'])
            ->where('overpayments', $validated['overpayments'])
            ->first();

        if (!is_null($overpaymentsSimulation)) {
            return back()->with([
                'alertType' => AlertType::INFO,
                'alertMessage' => __('messages.overpaymentSimulation.alreadyExist')
            ]);
        }

        OverpaymentSimulation::create($validated);

        return back()->with([
            'alertType' => AlertType::SUCCESS,
            'alertMessage' => __('messages.overpaymentSimulation.saved')
        ]);
    }

    public function destroy(OverpaymentSimulation $overpaymentSimulation): RedirectResponse
    {
        OverpaymentSimulation::destroy($overpaymentSimulation->id);

        return redirect()
            ->route('profil.overpayment.index')
            ->with([
                'alertType' => AlertType::SUCCESS,
                'alertMessage' => __('messages.overpaymentSimulation.deleted')
            ]);
    }
}
