<?php

declare(strict_types=1);

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
        $credits = Credit::with('bank')
            ->whereRelation('bank', 'slug', $bank)
            ->when($request->has('sort'), function ($query) use ($request) {
               $query->orderBy('created_at', $request->get('sort'));
            })
            ->when($request->has('marza'), function ($query) use ($request) {
                $query->orderBy('margin', $request->get('marza'));
            })
            ->when($request->has('prowizja'), function ($query) use ($request) {
                $query->orderBy('commission', $request->get('prowizja'));
            });

        return Inertia::render('OfferBank', [
            'credits' => $credits->get()
        ]);
    }

    public function showAll(Request $request): Response
    {
        $credits = Credit::with('wibor', 'bank')
            ->when($request->has('sort'), function ($query) use ($request) {
                $query->orderBy('created_at', $request->get('sort'));
            })
            ->when($request->has('marza'), function ($query) use ($request) {
                $query->orderBy('margin', $request->get('marza'));
            })
            ->when($request->has('prowizja'), function ($query) use ($request) {
                $query->orderBy('commission', $request->get('prowizja'));
            });

        return Inertia::render('AllOffers', [
            'credits' => $credits->get(),
            'creditCount' => $credits->count()
        ]);
    }

    public function showCredit($bank, $credit): Response
    {
        return Inertia::render('OfferCredit', [
            'credit' => Credit::whereRelation('bank', 'slug', $bank)
                ->with('bank', 'wibor')
                ->where('slug', $credit)
                ->first()
        ]);
    }
}
