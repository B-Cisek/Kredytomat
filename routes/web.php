<?php

use App\Http\Controllers\UserController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;



Route::get('/', function () {
    return Inertia::render('Home',[
        'loggedIn' => \Illuminate\Support\Facades\Auth::check()
    ]);
})->name('home');

Route::group(['middleware' => ['auth', 'admin'], 'prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::resource('users',UserController::class);
});


Route::get('/dashboard', function () {
    return Inertia::render('AdminDashboard',[
        'loggedIn' => \Illuminate\Support\Facades\Auth::check()
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/auth.php';

Route::get('/test', function () {
    return Inertia::render('Test', [
        'loggedIn' => \Illuminate\Support\Facades\Auth::check()
    ]);
});

