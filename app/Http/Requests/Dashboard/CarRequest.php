<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class CarRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $carId = $this->route('car');

        return [
            'brand_ar' => 'required|string|max:255',
            'brand_en' => 'nullable|string|max:255',
            'model_ar' => 'required|string|max:255',
            'model_en' => 'nullable|string|max:255',
            'year' => 'nullable|string|max:4',
            'color' => 'nullable|string|max:255',
            'is_active' => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'brand_ar.required' => 'الماركة بالعربي مطلوبة',
            'brand_ar.string' => 'الماركة بالعربي يجب أن تكون نص',
            'brand_ar.max' => 'الماركة بالعربي يجب ألا تتجاوز 255 حرف',
            'brand_en.string' => 'الماركة بالإنجليزي يجب أن تكون نص',
            'brand_en.max' => 'الماركة بالإنجليزي يجب ألا تتجاوز 255 حرف',
            'model_ar.required' => 'الموديل بالعربي مطلوب',
            'model_ar.string' => 'الموديل بالعربي يجب أن يكون نص',
            'model_ar.max' => 'الموديل بالعربي يجب ألا يتجاوز 255 حرف',
            'model_en.string' => 'الموديل بالإنجليزي يجب أن يكون نص',
            'model_en.max' => 'الموديل بالإنجليزي يجب ألا يتجاوز 255 حرف',
            'year.max' => 'السنة يجب ألا تتجاوز 4 أرقام',
            'color.max' => 'اللون يجب ألا يتجاوز 255 حرف',
        ];
    }
}
