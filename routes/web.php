<?php

use App\Http\Controllers\AboutCreditController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\BankController;
use App\Http\Controllers\Admin\CreditController;
use App\Http\Controllers\Admin\SendResetPasswordLink;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\CreditSimulationsController;
use App\Http\Controllers\ExtendedCalculatorController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InstallmentCalculatorController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\OverpaymentCalculatorController;
use App\Http\Controllers\OverpaymentSimulationsController;
use App\Http\Controllers\RrsoCalculatorController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\UserSimulationsController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', HomeController::class)->name('home');

Route::get('oferta', [OfferController::class, 'index'])->name('offer');
Route::get('oferta/wszystkie', [OfferController::class, 'showAll'])->name('offer.all');
Route::get('oferta/{bank}', [OfferController::class, 'show'])->name('offer.show');
Route::get('oferta/{bank}/{credit}', [OfferController::class, 'showCredit'])->name('offer.show.credit');

Route::get('o-kredycie', AboutCreditController::class)->name('about-credit');

Route::get('kalkulator-raty', InstallmentCalculatorController::class)->name('calculator.installment');
Route::get('kalkulator-rrso', RrsoCalculatorController::class)->name('calculator.rrso');
Route::get('kalkulator-rozszerzony', ExtendedCalculatorController::class)->name('calculator.extended');
Route::get('kalkulator-nadplata-kredytu', OverpaymentCalculatorController::class)->name('calculator.overpayment');

Route::group(['middleware' => 'auth'], function () {
    /* Profile */
    Route::get('profil' , [ProfileController::class, 'index'])->name('profil');
    Route::post('profil' , [ProfileController::class, 'profileUpdate'])->name('profil.update');
    Route::post('profil/zmiana-hasla' , [ProfileController::class, 'passwordUpdate'])->name('profil.password.update');
    Route::post('profil/usun-konto' , [ProfileController::class, 'deleteAccount'])->name('profil.delete');

    Route::post('zapisz-symulacje', [CreditSimulationsController::class, 'save'])
        ->name('credit-simulation.save');

    Route::get('twoje-symulacje', UserSimulationsController::class)
        ->name('profil.saved-simulations');


    Route::get('symulacje-kredytu', [CreditSimulationsController::class, 'index'])
        ->name('profil.credit.index');
    Route::get('symulacje-kredytu/{creditSimulation}', [CreditSimulationsController::class, 'show'])
        ->name('profil.credit.show');
    Route::delete('symulacje-kredytu/{creditSimulation}', [CreditSimulationsController::class, 'destroy'])
        ->name('profil.credit.destroy');



    Route::get('profil/zapisane-kalkulacje-nadplaty-kredytu', [OverpaymentSimulationsController::class, 'index'])
        ->name('profil.overpayment.index');
    Route::get('profil/zapisane-kalkulacje-nadplaty-kredytu/{overpaymentSimulation}', [OverpaymentSimulationsController::class, 'show'])
        ->name('profil.overpayment.show');
    Route::post('zapisz-kalkulacje-nadplaty', [OverpaymentSimulationsController::class, 'save'])
        ->name('profil.overpayment.save');
    Route::delete(
        uri: 'symulacja-nadplaty-kredytu/{overpaymentSimulation}',
        action: [OverpaymentSimulationsController::class, 'destroy']
    )->name('profil.overpayment.destroy');
});

Route::group(['middleware' => ['admin', 'auth'], 'prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::resource('users', UserController::class)->except(['show']);
    Route::get('users/reset-password', SendResetPasswordLink::class)->name('users.reset-password');
    Route::resource('credits', CreditController::class)->except(['show']);
    Route::resource('banks', BankController::class)->except(['show']);
    Route::get('dashboard', AdminDashboardController::class)->name('dashboard');
});


Route::get('test', function () {
    return Inertia::render('Test');
});
require __DIR__ . '/auth.php';

