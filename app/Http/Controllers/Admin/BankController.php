<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Enums\AlertType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Bank\StoreBankRequest;
use App\Http\Requests\Bank\UpdateBankRequest;
use App\Models\Bank;
use App\Services\ImageStore\ImageStoreService;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class BankController extends Controller
{
    public function __construct(private readonly ImageStoreService $imageStoreService)
    {
    }

    public function index(): Response
    {
        $banks = Bank::paginate(10);

        return Inertia::render('Admin/Banks/Index', [
            'banks' => $banks
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Banks/Create');
    }

    public function store(StoreBankRequest $request): RedirectResponse
    {
        $link = $this->imageStoreService
            ->store($request->file('logo'))
            ->getLink();

        Bank::create([
            'bank_name' => $request->bank_name,
            'slug' => $request->bank_name,
            'logo_path' => $link
        ]);

        return redirect()
            ->route('admin.banks.index')
            ->with([
                'alertType' => AlertType::SUCCESS,
                'alertMessage' => __('messages.bank.store')
            ]);
    }

    public function edit(Bank $bank): Response
    {
        return Inertia::render('Admin/Banks/Edit', [
            'bank' => $bank
        ]);
    }

    public function update(UpdateBankRequest $request, Bank $bank): RedirectResponse
    {
        $attributes = $request->validated();

        /* If logo exists in request delete old logo and save new */
        if ($attributes['logo']) {
            $parts = explode('storage/', $bank->logo_path); // TODO: refactor

            $this->imageStoreService->delete($parts[1]);
            $attributes['logo_path'] = $this->imageStoreService->store($request->file('logo'))->getLink();
        }

        $bank->update($attributes);

        return redirect()
            ->route('admin.banks.index')
            ->with([
                'alertType' => AlertType::SUCCESS,
                'alertMessage' => __('messages.bank.update')
            ]);
    }

    public function destroy(Bank $bank): RedirectResponse
    {
        $response = redirect()->route('admin.banks.index');

        try {
            $bank->delete();

            /* Delete logo from storage */
            $parts = explode('storage/', $bank->logo_path); // TODO: refactor
            $this->imageStoreService->delete($parts[1]);
        } catch (QueryException) {
            return $response->with([
                'alertType' => AlertType::DANGER,
                'alertMessage' => __('messages.bank.deleteFail')
            ]);
        }

        return $response->with([
            'alertType' => AlertType::SUCCESS,
            'alertMessage' => __('messages.bank.delete')
        ]);
    }

    public function massDestroy(Request $request): RedirectResponse
    {
        $response = redirect()->route('admin.banks.index');

        $ids = $request->get('ids') ?? [];

        if (empty($ids)) {
            return $response->with([
                'alertType' => AlertType::INFO,
                'alertMessage' => __('messages.bank.massDeleteFail')
            ]);
        }

        try {
            foreach ($ids as $id) {
                $bank = Bank::findOrFail($id);
                $bank->delete();

                $parts = explode('storage/', $bank->logo_path); // TODO: refactor
                $this->imageStoreService->delete($parts[1]);
            }
        } catch (QueryException) {
            return $response->with([
                'alertType' => AlertType::DANGER,
                'alertMessage' => __('messages.bank.deleteFail')
            ]);
        }

        return $response->with([
            'alertType' => AlertType::SUCCESS,
            'alertMessage' => __('messages.bank.massDelete')
        ]);
    }
}
