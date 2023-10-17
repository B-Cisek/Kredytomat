<?php

namespace App\Http\Requests\Credit;

use App\Rules\DetailsValidationRule;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCreditRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'credit_name' => ['required', 'string'],
            'amount_from' => ['required', 'numeric'],
            'amount_to' => ['required', 'numeric'],
            'period_from' => ['required', 'numeric'],
            'period_to' => ['required', 'numeric'],
            'margin' => ['required', 'numeric'],
            'commission' => ['required', 'numeric'],
            'wibor_id' => ['required', Rule::exists('wibors','id')],
            'bank_id' => ['required', Rule::exists('banks','id')],
            'details' => [new DetailsValidationRule]
        ];
    }
}
