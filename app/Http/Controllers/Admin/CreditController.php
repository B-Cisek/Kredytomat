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
        $attributes['details'] = $this->formatDetails($attributes['details']);

        Credit::create($attributes);

        return redirect()
            ->route('admin.credits.index')
            ->with([
                'alert_type' => AlertType::SUCCESS,
                'alert_message' => 'Kredyt poprawnie dodany!'
            ]);
    }

    private function formatDetails(string|null $details): string
    {
        $result = [];
        $lines = explode(';', $details);


        foreach ($lines as $line) {
            if (empty($line)) {
                continue;
            }
            $line = str_replace('\n', '', $line);
            $detail = explode(':', $line);
            $key = trim($detail[0]);
            $value = trim($detail[1]);
            $result[$key] = $value;
        }

        return json_encode($result);
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
        $attributes['details'] = $this->formatDetails($attributes['details']);

        $credit->update($attributes);

        return redirect()
            ->route('admin.credits.index')
            ->with([
                'alert_type' => AlertType::INFO,
                'alert_message' => __('dashboard.credit.updated')
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

    public function massDestroy()
    {
        $ids = [1, 2, 3];
        Credit::destroy($ids);

        return redirect()
            ->route('admin.credits.index')
            ->with([
                'alert_type' => AlertType::DANGER,
                'alert_message' => 'Usunięto zaznaczone kredyty!'
            ]);
    }
}
