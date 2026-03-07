<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTransactionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'type'             => ['required', 'in:income,expense'],
            'title'            => ['required', 'string', 'max:255'],
            'amount'           => ['required', 'numeric', 'min:0.01'],
            'category'         => ['required', 'string'],
            'description'      => ['nullable', 'string'],
            'transaction_date' => ['required', 'date'],
        ];
    }
}