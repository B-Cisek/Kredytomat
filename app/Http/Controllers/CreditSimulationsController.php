<?php

namespace App\Http\Controllers;

use App\Enums\AlertType;
use App\Models\CreditSimulation;
use App\Models\Wibor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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

    public function save(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'amount_of_credit' => 'required|numeric',
            'period' => 'required|numeric',
            'margin' => 'required|numeric',
            'commission' => 'required|numeric',
            'commission_type' => 'string',
            'type_of_installment' => 'required|string',
            'wibor_id' => 'required|numeric',
            'fixed_fees' => 'json',
            'changing_fees' => 'json',
            'interest_changes' => 'json'
        ]);

        if ($validated['commission_type'] === 'number') {
            $validated['commission'] =
                round(($validated['commission'] / $validated['amount_of_credit']) * 100, 2);
        }

        unset($validated['commission_type']);

        $wibor = Wibor::where('value', $validated['wibor_id'])->first();
        $validated['wibor_id'] = $wibor->id;

        $validated['user_id'] = Auth::id();

        $creditSimulation = CreditSimulation::where('amount_of_credit', $validated['amount_of_credit'])
            ->where('period', $validated['period'])
            ->where('margin', $validated['margin'])
            ->where('commission', $validated['commission'])
            ->where('type_of_installment', $validated['type_of_installment'])
            ->where('wibor_id', $validated['wibor_id'])
            ->where('fixed_fees', $validated['fixed_fees'])
            ->where('changing_fees', $validated['changing_fees'])
            ->first();

        if (!is_null($creditSimulation)) {
            return back()->with([
                'alert_type' => AlertType::WARNING,
                'alert_message' => 'Symulacja kredytu już istnieje!'
            ]);
        }

        CreditSimulation::create($validated);

        return back()->with([
            'alert_type' => AlertType::SUCCESS,
            'alert_message' => 'Zapisano symulacje kredytu!'
        ]);
    }

    public function destroy(CreditSimulation $creditSimulation)
    {
        CreditSimulation::destroy($creditSimulation->id);

        return redirect()
            ->route('profil.credit.index')
            ->with([
                'alert_type' => AlertType::SUCCESS,
                'alert_message' => 'Symulacja usunięta!'
            ]);
    }
}
