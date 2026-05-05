<?php

namespace App\Http\Services\Checkout;

use App\Helper\Helper;
use App\Models\Order;
use Illuminate\Support\Facades\Log;

/**
 * Service for handling invoice generation
 */
class InvoiceService
{
    use Helper;

    /**
     * Generate invoice for order
     */
    public function generateForOrder(Order $order): ?string
    {
        try {
            // Load relationships needed for invoice
            $order->load(['user', 'car', 'service', 'userPackage.package', 'choices']);

            $invoicePath = $this->generateInvoicePDF($order);
            
            if ($invoicePath) {
                $order->update(['invoice_url' => $invoicePath]);
                Log::info('Invoice generated successfully', ['order_id' => $order->id]);
            }

            return $invoicePath;
        } catch (\Exception $e) {
            Log::error('Invoice generation failed', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);
            // Don't throw - invoice can be generated later
            return null;
        }
    }
}
