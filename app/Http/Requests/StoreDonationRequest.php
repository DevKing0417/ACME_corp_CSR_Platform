<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDonationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'amount' => ['required', 'numeric', 'min:1'],
            'payment_method' => ['required', 'string', 'in:credit_card,paypal,bank_transfer'],
            'message' => ['nullable', 'string', 'max:500'],
            'is_anonymous' => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'amount.required' => 'Donation amount is required.',
            'amount.min' => 'Donation amount must be at least 1.',
            'payment_method.required' => 'Payment method is required.',
            'payment_method.in' => 'Invalid payment method.',
            'message.max' => 'Message may not be longer than 500 characters.',
        ];
    }
} 