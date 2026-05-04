<?php

namespace App\Http\Services\Payment;

use App\Models\Order;
use Illuminate\Support\Facades\Log;

/**
 * Main service orchestrating the payment callback flow
 */
class PaymentCallbackService
{
    public function __construct(
        protected OrderPaymentService $orderPaymentService,
        protected PaymentService $paymentService,
        protected CaptainAssignmentService $captainAssignmentService,
        protected InvoiceGenerationService $invoiceGenerationService
    ) {}

    /**
     * Process payment callback
     */
    public function processCallback(Order $order, string $paymentId): array
    {
        Log::info('Processing payment callback', [
            'order_number' => $order->number,
            'payment_id' => $paymentId
        ]);

        // Get payment details from Moyasar
        $payment = $this->orderPaymentService->getPaymentDetails($paymentId);

        // Check for authentication errors
        if ($this->orderPaymentService->isAuthenticationError($payment)) {
            return [
                'status' => 'error',
                'message' => __('general.Invalid_authorization_credentials'),
                'redirect' => route('user.payment', [$order->number, 'creditcard'])
            ];
        }

        // Prepare payment data
        $paymentData = $this->orderPaymentService->preparePaymentData($order, $payment);

        // Process payment through payment service
        $result = $this->paymentService->processPayment($paymentData);

        // Check if already paid
        if ($result === 'already_paid') {
            return [
                'status' => 'success',
                'message' => __('messages.PaidPayment'),
                'order' => $order
            ];
        }

        // Handle payment status
        return match ($payment['status']) {
            'paid' => $this->handleSuccessfulPayment($order, $payment),
            'failed' => $this->handleFailedPayment($order),
            default => $this->handleUnknownPaymentStatus($order),
        };
    }

    /**
     * Handle successful payment
     */
    protected function handleSuccessfulPayment(Order $order, array $payment): array
    {
        Log::info('Handling successful payment', ['order_id' => $order->id]);

        // Update order status
        $this->orderPaymentService->updateOrderAfterPayment($order, $payment);

        // Assign captain if booking is today
        $this->captainAssignmentService->assignCaptainIfToday($order);

        // Dispatch invoice generation job (async to prevent timeout)
        try {
            if ($this->invoiceGenerationService->shouldGenerateInvoice($order)) {
                Log::info('Dispatching invoice generation job', ['order_id' => $order->id]);
                
                \App\Jobs\GenerateInvoiceJob::dispatch($order->id)
                    ->onQueue('invoices');
                
                Log::info('Invoice generation job dispatched', ['order_id' => $order->id]);
            }
        } catch (\Throwable $e) {
            // Log error but don't stop payment process
            Log::error('Failed to dispatch invoice generation job', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);
            // Continue - invoice can be generated later manually
        }

        return [
            'status' => 'success',
            'message' => __('general.payment_successful'),
            'order' => $order->fresh()
        ];
    }

    /**
     * Handle failed payment
     */
    protected function handleFailedPayment(Order $order): array
    {
        Log::warning('Handling failed payment', ['order_id' => $order->id]);

        $this->orderPaymentService->updateOrderAfterFailedPayment($order);

        return [
            'status' => 'failed',
            'message' => __('general.payment_failed'),
            'order' => $order->fresh()
        ];
    }

    /**
     * Handle unknown payment status
     */
    protected function handleUnknownPaymentStatus(Order $order): array
    {
        Log::error('Unknown payment status', ['order_id' => $order->id]);

        return [
            'status' => 'error',
            'message' => __('general.payment_error'),
            'order' => $order
        ];
    }
}
