<?php

namespace App\Http\Requests\Transaction;

use Illuminate\Foundation\Http\FormRequest;

class TransactionTransferRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        return [
            'amount' => 'required|numeric|min:0.01',
            'recipient_id' => [
                'required',
                'exists:users,id',
                function ($attribute, $value, $fail) {
                    if ($value == auth()->id()) {
                        $fail('You cannot transfer to yourself.');
                    }
                },
            ],
            'description' => 'nullable|string|max:60000',
        ];
    }
    
    
}
