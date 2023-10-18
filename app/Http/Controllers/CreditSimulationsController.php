<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\AlertType;
use App\Http\Requests\CreditSimulationRequest;
use App\Models\CreditSimulation;
use App\Models\Wibor;
use App\Services\CommissionCalculatorService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class CreditSimulationsController extends Controller
{
    public function index(): Response
    {
        $userId = Auth::user()->id;

        $creditSimulations = CreditSimulation::with('wibor')
            ->where('user_id', $userId)
            ->paginate(10);

        return Inertia::render('CreditSimulations', [
            'creditSimulations' => $creditSimulations
        ]);
    }

    public function show($creditSimulation): Response
    {
        return Inertia::render('CreditSimulation', [
            'creditSimulation' => CreditSimulation::with('wibor')->findOrFail($creditSimulation)
        ]);
    }

    public function save(CreditSimulationRequest $request): RedirectResponse
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

        $creditSimulation = CreditSimulation::where('amount_of_credit', $validated['amount_of_credit'])
            ->where('period', $validated['period'])
            ->where('user_id', $validated['user_id'])
            ->where('start_date', $validated['start_date'])
            ->where('margin', $validated['margin'])
            ->where('commission', $validated['commission'])
            ->where('type_of_installment', $validated['type_of_installment'])
            ->where('wibor_id', $validated['wibor_id'])
            ->where('fixed_fees', $validated['fixed_fees'])
            ->where('changing_fees', $validated['changing_fees'])
            ->first();

        if (!is_null($creditSimulation)) {
            return back()->with([
                'alertType' => AlertType::WARNING,
                'alertMessage' => __('messages.creditSimulation.alreadyExist')
            ]);
        }

        CreditSimulation::create($validated);

        return back()->with([
            'alertType' => AlertType::SUCCESS,
            'alertMessage' => __('messages.creditSimulation.saved')
        ]);
    }

    public function destroy(CreditSimulation $creditSimulation): RedirectResponse
    {
        CreditSimulation::destroy($creditSimulation->id);

        return redirect()
            ->route('profil.credit.index')
            ->with([
                'alertType' => AlertType::SUCCESS,
                'alertMessage' => __('messages.creditSimulation.deleted')
            ]);
    }
}
