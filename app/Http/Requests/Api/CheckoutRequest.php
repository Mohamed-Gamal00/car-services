<?php

namespace App\Http\Requests\Api;

use App\Helper\ApiResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;

class CheckoutRequest extends FormRequest
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
        $isUsingPackage = $this->filled('user_package_id');

        return [
            'car_id' => 'required|exists:cars,id',
            'car_model' => 'required',
            'car_number' => 'required',
            'note' => 'nullable',
            'user_package_id' => 'nullable',
            'booking_date' => 'required|date|after_or_equal:today',
            'booking_time' => [
                'required',
                'date_format:H:i',
                function ($attribute, $value, $fail) {
                    $bookingDate = $this->input('booking_date');
                    
                    if (!$bookingDate) {
                        return;
                    }

                    // Check if booking date is today
                    $isToday = Carbon::parse($bookingDate)->isToday();
                    
                    if ($isToday) {
                        $currentTime = Carbon::now()->format('H:i');
                        $bookingTime = $value;
                        
                        // Compare times
                        if ($bookingTime < $currentTime) {
                            $fail('وقت الحجز يجب أن يكون بعد أو يساوي الوقت الحالي عند الحجز لنفس اليوم.');
                        }
                    }
                },
            ],
            'save_address_details' => 'boolean',
            'latitude' => 'required',
            'longitude' => 'required',
            'location' => 'nullable',
            'payment_method' => $isUsingPackage ? 'nullable' : 'required|in:creditcard,mada,applepay',
            'choices' => 'nullable|array',
            'choices.*' => 'nullable|integer|exists:choices,id',
            'images' => 'nullable|array',
            'images.*' => 'image',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'booking_date.after_or_equal' => 'تاريخ الحجز يجب أن يكون اليوم أو في المستقبل.',
            'booking_time.required' => 'وقت الحجز مطلوب.',
            'booking_time.date_format' => 'صيغة وقت الحجز غير صحيحة. يجب أن تكون بصيغة HH:MM.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        if ($this->is('api/*')) {
            $response = ApiResponse::sendResponse(422, $validator->errors()->first());
            throw new ValidationException($validator, $response);
        }
    }
}
