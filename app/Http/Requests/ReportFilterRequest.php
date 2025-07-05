<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReportFilterRequest extends FormRequest
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
            'report_type' => 'required|in:products-report,coupons-report,customers-report',
            'start_at' => 'required|date|before_or_equal:end_at',
            'end_at' => 'required|date|after_or_equal:start_at',
        ];
    }

    public function messages(): array
    {
        return [
            'report_type.required' => 'يرجى اختيار نوع التقرير.',
            'report_type.in' => 'نوع التقرير المحدد غير صالح.',
            'start_at.required' => 'يرجى تحديد تاريخ البداية.',
            'start_at.date' => 'تاريخ البداية غير صالح.',
            'start_at.before_or_equal' => 'تاريخ البداية يجب أن يكون قبل أو يساوي تاريخ النهاية.',
            'end_at.required' => 'يرجى تحديد تاريخ النهاية.',
            'end_at.date' => 'تاريخ النهاية غير صالح.',
            'end_at.after_or_equal' => 'تاريخ النهاية يجب أن يكون بعد أو يساوي تاريخ البداية.',
        ];
    }
}
