<?php

namespace App\Http\Requests\Dashboard\Captain;

use Illuminate\Foundation\Http\FormRequest;

class CaptainRequest extends FormRequest
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
        $rules = [
            'name' => 'required|string|max:255',
            'is_active' => 'nullable|boolean',
            'status' => 'nullable|in:available,busy',
            'phone' => [
                'required',
                'regex:/^05\d{8}$/',
            ],];

        return $rules;
    }
}
