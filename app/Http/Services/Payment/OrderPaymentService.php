<?php

namespace App\Http\Services\Payment;

use App\Models\Order;
use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Service for handling order payment operations
 */
class OrderPaymentService
{
    /**
     * Get payment details from Moyasar
     */
    public function getPaymentDetails(string $paymentId): array
    {
        $secret_key = Setting::pluck('secret_key')->first();
        $token = base64_encode($secret_key . ':');

        $payment = Http::baseUrl('https://api.moyasar.com/v1')
            ->withHeaders(['Authorization' => "Basic {$token}"])
            ->get("payments/{$paymentId}")
            ->json();

        Log::info('Payment details retrieved', [
            'payment_id' => $paymentId,
            'status' => $payment['status'] ?? 'unknown'
        ]);

        return $payment;
    }

    /**
     * Prepare payment data for processing
     */
    public function preparePaymentData(Order $order, array $payment): array
    {
        return [
            'user_id' => $order->user_id,
            'user_name' => $order->user->first_name . ' ' . $order->user->family_name,
            'order_number' => $order->number,
            'status' => $payment['status'],
            'source' => $payment['source']['company'] ?? 'unknown',
            'payment_id' => $payment['id'],
            'cur' => $payment['currency'],
            'amount' => $payment['amount'],
            'description' => $payment['description'] ?? null,
        ];
    }

    /**
     * Update order after successful payment
     */
    public function updateOrderAfterPayment(Order $order, array $payment): void
    {
        $order->update([
            'order_status_id' => 3, // Assigned status
            'payment_status' => 'paid',
            'payment_method' => $payment['source']['company'] ?? null,
        ]);

        Log::info('Order updated after payment', [
            'order_id' => $order->id,
            'order_number' => $order->number
        ]);
    }

    /**
     * Update order after failed payment
     */
    public function updateOrderAfterFailedPayment(Order $order): void
    {
        $order->update([
            'payment_status' => 'failed'
        ]);

        Log::warning('Order payment failed', [
            'order_id' => $order->id,
            'order_number' => $order->number
        ]);
    }

    /**
     * Check if payment is authentication error
     */
    public function isAuthenticationError(array $payment): bool
    {
        return isset($payment['type']) && $payment['type'] === 'authentication_error';
    }
}
