<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreditSimulationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'amount_of_credit' => 'required|numeric',
            'period' => 'required|numeric',
            'start_date' => 'required|json',
            'margin' => 'required|numeric',
            'commission' => 'required|numeric',
            'commission_type' => 'string',
            'type_of_installment' => 'required|string',
            'wibor_id' => 'required|numeric',
            'fixed_fees' => 'json',
            'changing_fees' => 'json',
            'interest_changes' => 'json'
        ];
    }
}
