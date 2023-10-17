<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Enums\AlertType;
use App\Helpers\DetailsFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Credit\StoreCreditRequest;
use App\Http\Requests\Credit\UpdateCreditRequest;
use App\Models\Bank;
use App\Models\Credit;
use App\Models\Wibor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CreditController extends Controller
{
    public function index(): Response
    {
        $credits = Credit::with('bank', 'wibor')
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

        if ($attributes['details'] !== null) {
            $attributes['details'] = DetailsFormatter::format($attributes['details']);
        }

        Credit::create($attributes);

        return redirect()
            ->route('admin.credits.index')
            ->with([
                'alertType' => AlertType::SUCCESS,
                'alertMessage' => __('messages.credit.store')
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

        if ($attributes['details'] !== null) {
            $attributes['details'] = DetailsFormatter::format($attributes['details']);
        }

        $credit->update($attributes);

        return redirect()
            ->route('admin.credits.index')
            ->with([
                'alertType' => AlertType::SUCCESS,
                'alertMessage' => __('messages.credit.update')
            ]);
    }

    public function destroy(Credit $credit): RedirectResponse
    {
        $credit->delete();

        return redirect()
            ->route('admin.credits.index')
            ->with([
                'alertType' => AlertType::SUCCESS,
                'alertMessage' => __('messages.credit.delete')
            ]);
    }

    public function massDestroy(Request $request): RedirectResponse
    {
        $response = redirect()->route('admin.credits.index');

        $ids = $request->get('ids') ?? [];

        if (empty($ids)) {
            return $response->with([
                'alertType' => AlertType::WARNING,
                'alertMessage' => __('messages.credit.massDeleteFail')
            ]);
        }

        Credit::destroy($ids);

        return $response->with([
            'alertType' => AlertType::SUCCESS,
            'alertMessage' => __('messages.credit.massDelete')
        ]);
    }
}
