<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Credit;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class OfferController extends Controller
{
    public function index(): Response
    {
        $banks = Bank::with('credits')
            ->withCount('credits')
            ->having('credits_count', '>', 0)
            ->get();

        return Inertia::render('Offer', [
            'banks' => $banks
        ]);
    }

    public function show(Request $request, $bank): Response
    {
        return Inertia::render('OfferBank', [
            'credits' => Credit::with('bank')
                ->whereRelation('bank', 'slug', $bank)
                ->get()
        ]);
    }

    public function showCredit($bank, $credit): Response
    {
        return Inertia::render('OfferCredit', [
            'credit' => Credit::whereRelation('bank', 'slug', $bank)
                ->where('slug', $credit)
                ->get()
        ]);
    }
}
