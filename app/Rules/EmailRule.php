<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class EmailRule implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     */
    public function passes($attribute, $value): bool
    {
        if (filter_var($value, FILTER_VALIDATE_EMAIL)){
            return true;
        }

        return false;
    }

    /**
     * Get the validation error message.
     *
     */
    public function message(): string
    {
        return __('validation.email');
    }
}
