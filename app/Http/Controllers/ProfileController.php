<?php

namespace App\Http\Controllers;

use App\Enums\AlertType;
use App\Http\Requests\Profile\UpdatePasswordRequest;
use App\Http\Requests\Profile\UpdateProfileRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Profile');
    }

    public function profileUpdate(UpdateProfileRequest $request): RedirectResponse
    {
        $user = Auth::user();
        $attributes = $request->validated();
        $user->update($attributes);

        return back()->with([
            'alert_type' => AlertType::SUCCESS,
            'alert_message' => 'Profil zaktualizowany!'
        ]);
    }

    public function passwordUpdate(UpdatePasswordRequest $request): RedirectResponse
    {
        $user = Auth::user();
        $attributes = $request->validated();

        if (!Hash::check($attributes['current_password'], $user->password)) {
            return back()->with([
                'alert_type' => AlertType::DANGER,
                'alert_message' => 'Aktualne hasło nie jest poprawne!'
            ]);
        }

        $attributes['password'] = Hash::make($attributes['password']);
        $user->update($attributes);

        return back()->with([
            'alert_type' => AlertType::SUCCESS,
            'alert_message' => 'Hasło zaktualizowane!'
        ]);
    }

    public function deleteAccount(Request $request): RedirectResponse|Redirector
    {
        $user = Auth::user();

        if (!Hash::check($request->get('password'), $user->getAuthPassword())) {
            throw ValidationException::withMessages([
                'password' => trans('auth.password'),
            ]);
        }

        $user->delete();

        return redirect(RouteServiceProvider::HOME)->with([
            'alert_type' => AlertType::SUCCESS,
            'alert_message' => 'Konto usunięte!'
        ]);
    }
}
