<?php

use App\Http\Controllers\BankController;
use App\Http\Controllers\CreditController;
use App\Http\Controllers\UserController;
use App\Models\Credit;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;


Route::get('/', function () {
    return Inertia::render('Home', [
        'credits' => Credit::with('bank')->get()
    ]);
})->name('home');

Route::get('/oferta', function () {
    return Inertia::render('Offer', [
        'credits' => Credit::all()
    ]);
})->name('offer');


Route::get('/faq', function () {
    return Inertia::render('Faq');
})->name('faq');

Route::get('/kalkulator-raty', function () {
    return Inertia::render('Calculator');
})->name('calculator.installment');

Route::get('/kalkulator-rrso', function () {
    return Inertia::render('CalculatorRrso');
})->name('calculator.rrso');



Route::group(['middleware' => ['admin', 'auth'], 'prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::resource('users', UserController::class)->except(['show']);
    Route::resource('credits', CreditController::class)->except(['show']);
    Route::resource('banks', BankController::class)->except(['show']);

    Route::get('dashboard', function () {
        return Inertia::render('Admin/AdminDashboard');
    })->name('dashboard');
});


require __DIR__ . '/auth.php';

Route::get('/test', function () {
    return Inertia::render('Index.vue', [
        'loggedIn' => \Illuminate\Support\Facades\Auth::check()
    ]);
});

