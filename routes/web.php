<?php

use App\Http\Controllers\AboutCreditController;
use App\Http\Controllers\Admin\BankController;
use App\Http\Controllers\Admin\CreditController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\CreditSimulationsController;
use App\Http\Controllers\ExtendedCalculatorController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InstallmentCalculatorController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\OverpaymentCalculatorController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RrsoCalculatorController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', HomeController::class)->name('home');

Route::get('/oferta', [OfferController::class, 'index'])->name('offer');
Route::get('/oferta/{name}', [OfferController::class, 'show'])->name('offer.show');

Route::get('/o-kredycie', AboutCreditController::class)->name('about-credit');

Route::get('/kalkulator-raty', InstallmentCalculatorController::class)->name('calculator.installment');
Route::get('/kalkulator-rrso', RrsoCalculatorController::class)->name('calculator.rrso');
Route::get('/kalkulator-rozszerzony', ExtendedCalculatorController::class)->name('calculator.extended');
Route::get('/nadplata-kredytu', OverpaymentCalculatorController::class)->name('calculator.overpayment');



Route::group(['middleware' => 'auth'], function () {
    Route::get('profil' , [ProfileController::class, 'index'])->name('profil');
    Route::post('profil' , [ProfileController::class, 'profileUpdate'])->name('profil.update');
    Route::post('profil/zmiana-hasla' , [ProfileController::class, 'passwordUpdate'])->name('profil.password.update');
    Route::post('profil/usun-konto' , [ProfileController::class, 'deleteAccount'])->name('profil.delete');

    Route::post('zapisz-symulacje', [CreditSimulationsController::class, 'save'])->name('credit-simulation.save');
});

Route::group(['middleware' => ['admin', 'auth'], 'prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::resource('users', UserController::class)->except(['show']);
    Route::resource('credits', CreditController::class)->except(['show']);
    Route::resource('banks', BankController::class)->except(['show']);

    Route::get('dashboard', function () {
        return Inertia::render('Admin/AdminDashboard');
    })->name('dashboard');
});


require __DIR__ . '/auth.php';

