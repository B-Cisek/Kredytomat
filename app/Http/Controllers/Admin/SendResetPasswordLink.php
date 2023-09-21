<?php

namespace App\Http\Controllers\Admin;

use App\Enums\AlertType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

class SendResetPasswordLink
{
    public function __invoke(Request $request)
    {
        if (is_null($request->get('email'))) {
            return back()->with([
                'alert_type' => AlertType::DANGER,
                'alert_message' => 'Brak adresu email!'
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
                'alert_type' => AlertType::SUCCESS,
                'alert_message' => 'Wysłano link do zmiany hasła.'
            ]);
        }

        throw ValidationException::withMessages([
            'email' => [trans($status)],
        ]);
    }
}
