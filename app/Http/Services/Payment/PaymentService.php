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
        // Check if payment already exists by payment_id
        $existingPayment = Payment::where('payment_id', $paymentData['payment_id'])->first();

        if ($existingPayment) {
            if ($existingPayment->status === 'paid') {
                return 'already_paid';
            }
            
            // Update existing payment if status changed
            $existingPayment->update([
                'status' => $paymentData['status'],
                'source' => $paymentData['source'],
                'amount' => $paymentData['amount'],
                'description' => $paymentData['description'] ?? null,
            ]);
            
            Log::info('Payment updated', ['payment_id' => $paymentData['payment_id']]);
            return 'updated';
        }

        // Generate unique reference if not provided
        $reference = $paymentData['package_reference'] ?? $paymentData['order_number'] ?? \Illuminate\Support\Str::uuid()->toString();

        try {
            Log::info('Creating new payment', ['payment_id' => $paymentData['payment_id']]);
            
            // Create a new payment record
            Payment::create([
                'user_id' => $paymentData['user_id'],
                'user_name' => $paymentData['user_name'] ?? null,
                'order_number' => $paymentData['order_number'] ?? null,
                'package_reference' => $paymentData['package_reference'] ?? null,
                'reference' => $reference,
                'status' => $paymentData['status'],
                'source' => $paymentData['source'] ?? null,
                'payment_id' => $paymentData['payment_id'],
                'cur' => $paymentData['cur'] ?? 'SAR',
                'amount' => $paymentData['amount'],
                'description' => $paymentData['description'] ?? null,
            ]);

            Log::info('Payment created successfully', ['payment_id' => $paymentData['payment_id']]);
            return 'created';
            
        } catch (\Illuminate\Database\QueryException $e) {
            // Handle unique constraint violations
            if ($e->getCode() === '23000') {
                Log::error('Duplicate payment attempt', [
                    'payment_id' => $paymentData['payment_id'],
                    'reference' => $reference,
                    'error' => $e->getMessage()
                ]);
                
                // Try to find the existing payment by reference
                $existingByRef = Payment::where('reference', $reference)->first();
                if ($existingByRef && $existingByRef->status === 'paid') {
                    return 'already_paid';
                }
                
                throw $e;
            }
            
            throw $e;
        }
    }
}
