<?php

namespace App\Http\Requests\Profile;

use App\Rules\EmailRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => [
                'required',
                'min:5',
                'max:255',
                Rule::unique('users', 'name')->ignore($this->user()->id)
            ],
            'email' => [
                'required',
                new EmailRule,
                Rule::unique('users', 'email')->ignore($this->user()->id)
            ]
        ];
    }
}
