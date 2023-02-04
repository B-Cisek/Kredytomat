<?php

use App\Http\Controllers\BankController;
use App\Http\Controllers\CreditController;
use App\Http\Controllers\UserController;
use App\Models\Credit;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;


Route::get('/', function () {
    return Inertia::render('Home', [
        'credits' => Credit::with('bank', 'wibor')->get()
    ]);
})->name('home');

Route::get('/oferta', function () {
    return Inertia::render('Offer', [
        'credits' => Credit::all()
    ]);
})->name('offer');

Route::get('/o-kredycie', function () {
    return Inertia::render('Okredycie');
})->name('o-kredycie');


Route::get('/kalkulator-raty', function () {
    return Inertia::render('Calculator');
})->name('calculator.installment');

Route::get('/kalkulator-rrso', function () {
    return Inertia::render('CalculatorRrso');
})->name('calculator.rrso');

Route::get('/kalkulator-rozszerzony', function () {
    return Inertia::render(
        'CalculatorExtended', [
            'wiborList' => \App\Models\Wibor::all()
        ]);
})->name('calculator.extended');

Route::get('/nadplata-kredytu', function () {
    return Inertia::render(
        'CreditOverpaymentView', [
        'wiborList' => \App\Models\Wibor::all()
    ]);
})->name('calculator.overpayment');

Route::get('/profil', function () {
    return Inertia::render('MyProfil');
})->name('profil');


Route::group(['middleware' => ['admin', 'auth'], 'prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::resource('users', UserController::class)->except(['show']);
    Route::resource('credits', CreditController::class)->except(['show']);
    Route::resource('banks', BankController::class)->except(['show']);

    Route::get('dashboard', function () {
        return Inertia::render('Admin/AdminDashboard');
    })->name('dashboard');
});


require __DIR__ . '/auth.php';


Route::post('/test', function (\Illuminate\Http\Request $request) {
   dd($request->all());
});

