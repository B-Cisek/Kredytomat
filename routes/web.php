<?php

use App\Http\Controllers\Admin\BankController;
use App\Http\Controllers\Admin\CreditController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\ProfileController;
use App\Models\Credit;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Home', [
        'credits' => Credit::with('bank', 'wibor')
            ->latest()
            ->limit(6)
            ->get()
    ]);
})->name('home');


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


Route::get('/oferta', [OfferController::class, 'index'])->name('offer');
Route::get('/oferta/{name}', [OfferController::class, 'show'])->name('offer.show');


Route::group(['middleware' => 'auth'], function () {
    Route::get('profil' , [ProfileController::class, 'index'])->name('profil');
    Route::post('profil' , [ProfileController::class, 'profileUpdate'])->name('profil.update');
    Route::post('profil/zmiana-hasla' , [ProfileController::class, 'passwordUpdate'])->name('profil.password.update');
    Route::post('profil/usun-konto' , [ProfileController::class, 'deleteAccount'])->name('profil.delete');
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

