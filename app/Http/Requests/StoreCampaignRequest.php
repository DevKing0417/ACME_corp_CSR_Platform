<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCampaignRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'target_amount' => ['required', 'numeric', 'min:1'],
            'start_date' => ['required', 'date', 'after_or_equal:today'],
            'end_date' => ['required', 'date', 'after:start_date'],
            'category' => ['required', 'string', 'max:255'],
            'image_url' => ['nullable', 'url', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'A campaign title is required.',
            'description.required' => 'A campaign description is required.',
            'target_amount.required' => 'A target amount is required.',
            'target_amount.min' => 'The target amount must be at least 1.',
            'start_date.after_or_equal' => 'The start date must be today or later.',
            'end_date.after' => 'The end date must be after the start date.',
            'category.required' => 'A category is required.',
            'image_url.url' => 'The image URL must be a valid URL.',
        ];
    }
} 