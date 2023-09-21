<?php

namespace App\Http\Requests\User;


use App\Rules\EmailRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => [
                'required',
                'min:5',
                'max:255',
                Rule::unique('users', 'name')
            ],
            'email' => [
                'required',
                new EmailRule,
                Rule::unique('users', 'email')
            ],
            'password' => [
                'required',
                'min:6',
                'max:255',
                'confirmed'
            ],
            'password_confirmation' => [
                'required',
                'min:6',
                'max:255',
            ]
        ];
    }
}
