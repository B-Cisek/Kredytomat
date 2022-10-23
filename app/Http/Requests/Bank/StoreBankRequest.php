<?php

namespace App\Http\Requests\Bank;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreBankRequest extends FormRequest
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
            'bank_name' => [
                'required',
                'min:3',
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
