<?php

namespace App\Http\Controllers\Admin;

use App\Enums\AlertType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Credit\StoreCreditRequest;
use App\Http\Requests\Credit\UpdateCreditRequest;
use App\Models\Bank;
use App\Models\Credit;
use App\Models\Wibor;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class CreditController extends Controller
{
    public function index(): Response
    {
        $credits = Credit::with('bank')
            ->paginate(10);

        return Inertia::render('Admin/Credits/Index', [
            'credits' => $credits
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Credits/Create', [
            'banks' => Bank::all(),
            'wibors' => Wibor::all()
        ]);
    }

    public function store(StoreCreditRequest $request): RedirectResponse
    {
        $attributes = $request->validated();
        $attributes['slug'] = $attributes['credit_name'];

        Credit::create($attributes);

        return redirect()
            ->route('admin.credits.index')
            ->with([
                'alert_type' => AlertType::SUCCESS,
                'alert_message' => 'Kredyt poprawnie dodany!'
            ]);
    }

    public function edit(Credit $credit): Response
    {
        return Inertia::render('Admin/Credits/Edit', [
            'credit' => $credit,
            'banks' => Bank::all(),
            'wibors' => Wibor::all()
        ]);
    }

    public function update(UpdateCreditRequest $request, Credit $credit): RedirectResponse
    {
        $attributes = $request->validated();

        $credit->update($attributes);

        return redirect()
            ->route('admin.credits.index')
            ->with([
                'alert_type' => AlertType::INFO,
                'alert_message' => 'Kredyt zaktualizowany!'
            ]);
    }

    public function destroy(Credit $credit): RedirectResponse
    {
        $credit->delete();

        return redirect()
            ->route('admin.credits.index')
            ->with([
                'alert_type' => AlertType::DANGER,
                'alert_message' => 'Kredyt usunięty!'
            ]);
    }
}
