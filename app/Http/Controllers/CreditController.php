<?php

namespace App\Http\Controllers;

use App\Http\Requests\Credit\StoreCreditRequest;
use App\Http\Requests\Credit\UpdateCreditRequest;
use App\Models\Bank;
use App\Models\Credit;
use Inertia\Inertia;

class CreditController extends Controller
{
    /**
     * @return \Inertia\Response
     */
    public function index()
    {
        $credits = Credit::with('bank')
            ->paginate(10);

        return Inertia::render('Admin/Credits/Index',[
            'credits' => $credits
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Inertia\Response
     */
    public function create()
    {
        return Inertia::render('Admin/Credits/Create', [
            'banks' => Bank::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Credit\StoreCreditRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreCreditRequest $request)
    {
        $attributes = $request->validated();

        Credit::create($attributes);

        return redirect()
            ->route('admin.credits.index')
            ->with('message', 'Kredyt poprawnie dodany!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Credit  $credit
     * @return \Illuminate\Http\Response
     */
    public function show(Credit $credit)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Credit  $credit
     * @return \Illuminate\Http\Response
     */
    public function edit(Credit $credit)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Credit\UpdateCreditRequest  $request
     * @param  \App\Models\Credit  $credit
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCreditRequest $request, Credit $credit)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Credit  $credit
     * @return \Illuminate\Http\Response
     */
    public function destroy(Credit $credit)
    {
        //
    }
}
