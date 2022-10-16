<?php

use App\Http\Controllers\BankController;
use App\Http\Controllers\CreditController;
use App\Http\Controllers\UserController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;


Route::get('/', function () {
    return Inertia::render('Home', [
        'loggedIn' => \Illuminate\Support\Facades\Auth::check()
    ]);
})->name('home');

Route::get('/oferta', function () {
    return Inertia::render('Offer', [
        'loggedIn' => \Illuminate\Support\Facades\Auth::check()
    ]);
})->name('offer');


Route::get('/faq', function () {
    return Inertia::render('Faq');
})->name('faq');


Route::group(['middleware' => ['admin', 'auth'], 'prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::resource('users', UserController::class)->except(['show']);
    Route::resource('credits', CreditController::class);
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

