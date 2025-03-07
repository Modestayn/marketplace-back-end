<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'name' => 'required|string|max:255',
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('categories')->ignore($this->category)
            ],
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:255',
            'image_url' => 'nullable|url|max:2048',
            'parent_id' => 'nullable|exists:categories,id',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
        ];

        // For PATCH requests, make required fields optional
        if ($this->isMethod('PATCH')) {
            $rules = collect($rules)->map(function ($rule, $field) {
                if (is_array($rule) && in_array('required', $rule)) {
                    return array_diff($rule, ['required']);
                }

                return str_replace('required', 'nullable', $rule);
            })->all();
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Category name is required',
            'slug.unique' => 'This category slug is already in use',
            'parent_id.exists' => 'The selected parent category does not exist',
            'image_url.url' => 'The image URL must be a valid URL',
        ];
    }
}
