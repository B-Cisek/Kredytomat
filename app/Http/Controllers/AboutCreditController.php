<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;

class AboutCreditController extends Controller
{
    public function __invoke(): Response
    {
        return Inertia::render('AboutCredit');
    }
}
