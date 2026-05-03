<?php

namespace App\Http\Requests\Dashboard\Package;

use Illuminate\Foundation\Http\FormRequest;

class PackageRequest extends FormRequest
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
    public function rules(): array
    {

        return [
            'name' => ['required', 'string', 'max:255'],
            'name_en' => ['nullable', 'string', 'max:255'],
            'price' => ['required', 'numeric'],
            'wash_count' => ['required', 'numeric'],
            'validity_days' => ['required', 'numeric'],
            'icon' => ['nullable', 'image'],
            'duration' => ['required', 'string'],
            'image' => ['nullable', 'image'],
            'image_en' => ['nullable', 'image'],
            'is_active' => ['nullable', 'in:1,0'],
            'description' => ['nullable', 'string'],
            'feature_name' => 'nullable|string|max:255',
            'feature_name_en' => 'nullable|string|max:255',
            'feature_description' => 'nullable|string|max:255',

        ];
    }
}
