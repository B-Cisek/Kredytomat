<?php

namespace App\Http\Controllers;

use App\Models\Wibor;
use Inertia\Inertia;
use Inertia\Response;

class ExtendedCalculatorController extends Controller
{
    public function __invoke(): Response
    {
        return Inertia::render('CalculatorExtended', [
            'wiborList' => Wibor::all()
        ]);
    }
}
