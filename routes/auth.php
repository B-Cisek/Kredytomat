<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('rejestracja', [RegisteredUserController::class, 'create'])
                ->name('register');

    Route::post('rejestracja', [RegisteredUserController::class, 'store']);

    Route::get('logowanie', [AuthenticatedSessionController::class, 'create'])
                ->name('login');

    Route::post('logowanie', [AuthenticatedSessionController::class, 'store']);

    Route::get('odzyskiwanie-hasla', [PasswordResetLinkController::class, 'create'])
                ->name('password.request');

    Route::post('odzyskiwanie-hasla', [PasswordResetLinkController::class, 'store'])
                ->name('password.email');

    Route::get('odzyskiwanie-hasla/{token}', [NewPasswordController::class, 'create'])
                ->name('password.reset');

    Route::post('zresetuj-haslo', [NewPasswordController::class, 'store'])
                ->name('password.update');
});

Route::middleware('auth')->group(function () {
    Route::get('potwierdz-haslo', [ConfirmablePasswordController::class, 'show'])
                ->name('password.confirm');

    Route::post('potwierdz-haslo', [ConfirmablePasswordController::class, 'store']);

    Route::post('wyloguj', [AuthenticatedSessionController::class, 'destroy'])
                ->name('logout');
});
