<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UseCouponRequest extends FormRequest
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
        return [
            'discount_code' => 'required|string|exists:discount_codes,code',
            'service_id' => 'required|exists:products,id',
            'order_total_price' => 'required|numeric|min:0',
        ];
    }

    public function messages()
    {
        return [
            'discount_code.required' => 'The discount code is required.',
            'discount_code.exists' => 'The discount code is invalid.',
            'service_id.required' => 'The service ID is required.',
            'service_id.exists' => 'The selected service does not exist.',
            'order_total_price.required' => 'The total order price is required.',
            'order_total_price.numeric' => 'The total order price must be a valid number.',
            'order_total_price.min' => 'The total order price must be greater than zero.',
        ];
    }
}
