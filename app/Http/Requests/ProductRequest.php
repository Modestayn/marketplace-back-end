<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image_url' => 'nullable|url|max:2048',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'is_available' => 'boolean',
        ];

        // If this is an update request, make some fields optional
        if ($this->isMethod('PATCH')) {
            $rules = collect($rules)->map(function ($rule, $field) {
                // Skip modifying already nullable fields
                if (str_contains($rule, 'nullable')) {
                    return $rule;
                }

                // Make all required fields optional for PATCH requests
                return str_replace('required', 'nullable', $rule);
            })->all();
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'name.required' => 'A product name is required',
            'category_id.required' => 'The product must belong to a category',
            'category_id.exists' => 'The selected category does not exist',
            'price.required' => 'A product price is required',
            'price.numeric' => 'The price must be a valid number',
            'price.min' => 'The price cannot be negative',
            'image_url.url' => 'The image URL must be a valid URL',
        ];
    }
}
