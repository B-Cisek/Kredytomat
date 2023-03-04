<?php

namespace App\Http\Controllers;

use App\Models\Credit;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function __invoke(): Response
    {
        return Inertia::render('Home', [
            'credits' => Credit::with('bank', 'wibor')
                ->latest()
                ->limit(6)
                ->get()
        ]);
    }
}
