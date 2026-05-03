<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DiscountCodeRequest extends FormRequest
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
        $discountCodeId = $this->route('discount_code');
        return [
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:discount_codes,code,' . $discountCodeId,
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive',
            'number_of_used' => 'required|numeric|min:0',
            'service_ids' => 'nullable|array',
            'service_ids.*' => 'nullable|numeric|exists:services,id',
            'discount_type' => 'required|in:percentage,price'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'اسم كود الخصم مطلوب',
            'code.required' => 'الكود مطلوب',
            'code.unique' => 'هذا الكود مستخدم بالفعل',
            'price.required' => 'قيمة الخصم مطلوبة',
            'price.numeric' => 'قيمة الخصم يجب أن تكون رقم',
            'price.min' => 'قيمة الخصم يجب أن تكون أكبر من أو تساوي 0',
            'status.required' => 'حالة الكود مطلوبة',
            'status.in' => 'حالة الكود يجب أن تكون نشط أو غير نشط',
            'number_of_used.required' => 'عدد مرات الاستخدام مطلوب',
            'number_of_used.numeric' => 'عدد مرات الاستخدام يجب أن يكون رقم',
            'service_ids.array' => 'الخدمات يجب أن تكون مصفوفة',
            'service_ids.*.exists' => 'الخدمة المحددة غير موجودة',
            'discount_type.required' => 'نوع الخصم مطلوب',
            'discount_type.in' => 'نوع الخصم يجب أن يكون نسبة مئوية أو قيمة ثابتة',
        ];
    }
}
