<?php

namespace App\Http\Controllers;

use App\Http\Requests\Bank\StoreBankRequest;
use App\Http\Requests\Bank\UpdateBankRequest;
use App\Models\Bank;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class BankController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     */
    public function index(): \Inertia\Response
    {
        $banks = Bank::paginate(10);

        return Inertia::render('Admin/Banks/Index', [
            'banks' => $banks
        ]);


    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Inertia\Response
     */
    public function create()
    {
        return Inertia::render('Admin/Banks/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Bank\StoreBankRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreBankRequest $request)
    {
        $logo = $request->file('logo')?->store('logos', 'public');

        Bank::create([
            'bank_name' => $request->bank_name,
            'logo_path' => $logo
        ]);

        return redirect()
            ->route('admin.banks.index')
            ->with([
                'alert_type' => 'success',
                'alert_message' => 'Bank dodany poprawnie!'
            ]);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Bank  $bank
     * @return \Inertia\Response
     */
    public function edit(Bank $bank)
    {
        return Inertia::render('Admin/Banks/Edit', [
            'bank' => $bank
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Bank\UpdateBankRequest  $request
     * @param  \App\Models\Bank  $bank
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateBankRequest $request, Bank $bank)
    {
        $attributes = $request->validated();

        if ($request->logo) {
            // Delete old logo
            $pathToLogo = str_replace('http://localhost/storage/', '', $bank->logo_path);
            Storage::disk('public')->delete($pathToLogo);

            // Save new logo
            $attributes['logo_path'] = $request->file('logo')->store('logos', 'public');
            $bank->update($attributes);
        }

        $bank->update($attributes);

        return redirect()
            ->route('admin.banks.index')
            ->with([
                'alert_type' => 'info',
                'alert_message' => 'Bank zaktualizowany!'
            ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Bank  $bank
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Bank $bank)
    {
        $pathToLogo = str_replace('http://localhost/storage/', '', $bank->logo_path);

        //Storage::disk('public')->exists($pathToLogo));
        Storage::disk('public')->delete($pathToLogo);
        $bank->delete();

        return redirect()
            ->route('admin.banks.index')
            ->with([
                'alert_type' => 'danger',
                'alert_message' => 'Bank usunięty!'
            ]);
    }
}
