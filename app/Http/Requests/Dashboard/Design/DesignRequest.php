<?php

namespace App\Http\Requests\Dashboard\Design;

use Illuminate\Foundation\Http\FormRequest;

class DesignRequest extends FormRequest
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
        $rules = [
            'title' => 'nullable|max:255',
            'description' => 'nullable',
        ];

        if ($this->isMethod('post')) {
            $rules['header_image'] = ['required', 'image'];
            $rules['header_image_en'] = ['required', 'image'];
        }

        if ($this->isMethod('put') || $this->isMethod('patch')) {
            $rules['header_image'] = ['nullable', 'image'];
            $rules['header_image_en'] = ['nullable', 'image'];
        }

        return $rules;
    }

}
