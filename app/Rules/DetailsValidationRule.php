<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class DetailsValidationRule implements Rule
{
    public function passes($attribute, $value): bool
    {
        if (empty($value)) {
            return true;
        }

        return str_contains($value, ':') && str_contains($value, ';');
    }

    public function message(): string
    {
        return 'Nieprawidłowy format';
    }
}
