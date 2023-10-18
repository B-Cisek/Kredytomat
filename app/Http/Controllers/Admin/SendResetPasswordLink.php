<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Enums\AlertType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

class SendResetPasswordLink
{
    # TODO: CREATE SERVICE TO SEND RESET LINK
    public function __invoke(Request $request): RedirectResponse
    {
        if (is_null($request->get('email'))) {
            return back()->with([
                'alertType' => AlertType::WARNING,
                'alertMessage' => __('messages.sendResetPasswordLink.noEmail')
            ]);
        }

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
