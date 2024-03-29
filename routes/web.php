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
use App\Http\Controllers\ScheduleCalculator;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\UserSimulationsController;
use App\Services\CreditCalculations\Enum\CommissionType;
use App\Services\CreditCalculations\Enum\PeriodType;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

Route::get('oferta', [OfferController::class, 'index'])->name('offer');
Route::get('oferta/wszystkie', [OfferController::class, 'showAll'])->name('offer.all');
Route::get('oferta/{bank}', [OfferController::class, 'show'])->name('offer.show');
Route::get('oferta/{bank}/{credit}', [OfferController::class, 'showCredit'])->name('offer.show.credit');
Route::get('o-kredycie', AboutCreditController::class)->name('about-credit');
Route::get('kalkulator-raty', InstallmentCalculatorController::class)->name('calculator.installment');
Route::get('kalkulator-rozszerzony', ExtendedCalculatorController::class)->name('calculator.extended');
Route::get('kalkulator-nadplaty', OverpaymentCalculatorController::class)->name('calculator.overpayment');

Route::group(['middleware' => 'auth'], function () {
    Route::get('profil', [ProfileController::class, 'index'])->name('profil');
    Route::post('profil', [ProfileController::class, 'profileUpdate'])->name('profil.update');
    Route::post('profil/zmiana-hasla', [ProfileController::class, 'passwordUpdate'])->name('profil.password.update');
    Route::post('profil/usun-konto', [ProfileController::class, 'deleteAccount'])->name('profil.delete');

    Route::post('zapisz-symulacje', [CreditSimulationsController::class, 'save'])
        ->name('credit-simulation.save');

    Route::get('moje-symulacje', UserSimulationsController::class)
        ->name('profil.saved-simulations');

    Route::get('symulacje-kredytu', [CreditSimulationsController::class, 'index'])
        ->name('profil.credit.index');
    Route::get('symulacje-kredytu/{creditSimulation}', [CreditSimulationsController::class, 'show'])
        ->name('profil.credit.show');
    Route::delete('symulacje-kredytu/{creditSimulation}', [CreditSimulationsController::class, 'destroy'])
        ->name('profil.credit.destroy');

    Route::get('profil/zapisane-kalkulacje-nadplaty', [OverpaymentSimulationsController::class, 'index'])
        ->name('profil.overpayment.index');
    Route::get('profil/zapisane-kalkulacje-nadplaty/{overpaymentSimulation}', [OverpaymentSimulationsController::class, 'show'])
        ->name('profil.overpayment.show');
    Route::post('zapisz-kalkulacje-nadplaty', [OverpaymentSimulationsController::class, 'save'])
        ->name('profil.overpayment.save');
    Route::delete('symulacja-nadplaty-kredytu/{overpaymentSimulation}', [OverpaymentSimulationsController::class, 'destroy'])
        ->name('profil.overpayment.destroy');
});

Route::group(['middleware' => ['admin', 'auth'], 'prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::resource('users', UserController::class)->except(['show']);
    Route::post('users/massDelete', [UserController::class, 'massDestroy'])->name('users.massDelete');
    Route::get('uzytkownicy/resetowanie-hasla', SendResetPasswordLink::class)->name('users.reset-password');
    Route::resource('credits', CreditController::class)->except(['show']);
    Route::post('credits/massDelete', [CreditController::class, 'massDestroy'])->name('credits.massDelete');
    Route::resource('banks', BankController::class)->except(['show']);
    Route::post('banks/massDelete', [BankController::class, 'massDestroy'])->name('banks.massDelete');
    Route::get('dashboard', AdminDashboardController::class)->name('dashboard');
});

Route::post('harmonogram', ScheduleCalculator::class)->name('get-schedule');


Route::get('test', function () {

    $credit = new \App\Services\CreditCalculations\Credit(
        300_000,
        20,
        PeriodType::YEAR,
        8.72,
        0,
        0,
        CommissionType::NUMBER
    );

    $interestsRateChanges = [
        [
            'date' => [
                'month' => 10,
                'year' => 2024
            ],
            'value' => 5.72
        ],
        [
            'date' => [
                'month' => 4,
                'year' => 2025
            ],
            'value' => 8
        ],
        [
            'date' => [
                'month' => 2,
                'year' => 2027
            ],
            'value' => 7.52
        ]
    ];


//    $overpayments = [
//        [
//            'start' => [
//                'month' => 11,
//                'year' => 2023
//            ],
//            'end' => [
//                'month' => 11,
//                'year' => 2023
//            ],
//
//            'overpayment' => 5_000
//        ],
////        [
////            'date' => [
////                'start' => [
////                    'month' => 12,
////                    'year' => 2025
////                ],
////                'end' => [
////                    'month' => 12,
////                    'year' => 2025
////                ]
////            ],
////            'value' => 35_500
////        ],
//    ];

    $creditCalculation = \App\Services\CreditCalculations\CreditCalculationsFactory::createCreditCalculation(
        \App\Services\CreditCalculations\Enum\TypeOfInstallment::EQUAL,
        \Carbon\Carbon::create(2023, 1),
        $credit,
        interestsRateChanges: $interestsRateChanges,
    );

    $schedule = $creditCalculation->schedule()->get();

    dd(
        \App\Services\CreditCalculations\ScheduleFormatter::format($schedule),
        \App\Services\CreditCalculations\ScheduleFormatter::format($schedule)[array_key_last($schedule)]
    );
});
require __DIR__ . '/auth.php';

