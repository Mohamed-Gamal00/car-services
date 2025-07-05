<?php

namespace App\Http\Services\Payment;

use App\Models\Payment;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    /**
     * Handle payment record creation or update.
     *
     * @param array $paymentData
     * @return string
     */
    public function processPayment(array $paymentData): string
    {
        $existingPayment = Payment::where('payment_id', $paymentData['payment_id'])->first();

        if ($existingPayment && $existingPayment->status === 'paid') {
            return 'already_paid';
        }

        Log::info('payment created');
        // Create a new payment record
        Payment::create([
            'user_id' => $paymentData['user_id'],
            'user_name' => $paymentData['user_name'],
            'order_number' => $paymentData['order_number'] ?? null,
            'package_reference' => $paymentData['package_reference'] ?? null,
            'status' => $paymentData['status'],
            'source' => $paymentData['source'],
            'payment_id' => $paymentData['payment_id'],
            'cur' => $paymentData['cur'],
            'amount' => $paymentData['amount'],
            'description' => $paymentData['description'] ?? null,
        ]);

        return true;
    }
}
