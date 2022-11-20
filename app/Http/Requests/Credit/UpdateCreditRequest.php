<?php

namespace App\Http\Requests\Credit;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCreditRequest extends FormRequest
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
            'credit_name' => ['required'],
            'amount_from' => ['required'],
            'amount_to' => ['required'],
            'period_from' => ['required'],
            'period_to' => ['required'],
            'margin' => ['required'],
            'commission' => ['required'],
            'wibor_id' => ['required'],
            'bank_id' => ['required', Rule::exists('banks','id')]
        ];
    }
}
