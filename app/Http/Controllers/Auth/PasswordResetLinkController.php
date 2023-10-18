<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Enums\AlertType;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class PasswordResetLinkController extends Controller
{
    public function create(): Response
    {
        return Inertia::render('Auth/ForgotPassword', [
            'status' => session('status'),
        ]);
    }

    # TODO: CREATE SERVICE TO SEND RESET LINK
    public function store(Request $request): RedirectResponse|ValidationException
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status == Password::RESET_LINK_SENT) {
            return back()->with([
                'status' => __($status),
                'alertType' => AlertType::SUCCESS,
                'alertMessage' => __('messages.sendResetPasswordLink.emailSent')
            ]);
        }

        throw ValidationException::withMessages([
            'email' => [trans($status)],
        ]);
    }
}
