<?php

namespace App\Http\Requests\Bank;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreBankRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'bank_name' => [
                'required',
                'min:3',
                'max:120',
                Rule::unique('banks', 'bank_name')
            ],
            'logo' => [
                'required',
                'image',
                'mimes:jpeg,jpg,png,gif,svg',
                'max:1024'
            ]
        ];
    }
}
