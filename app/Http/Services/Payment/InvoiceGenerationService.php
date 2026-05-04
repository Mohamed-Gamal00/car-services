<?php

namespace App\Http\Services\Payment;

use App\Helper\Helper;
use App\Models\Order;
use Illuminate\Support\Facades\Log;

/**
 * Service for handling invoice generation
 */
class InvoiceGenerationService
{
    use Helper;

    /**
     * Generate invoice for order
     */
    public function generateInvoice(Order $order): ?string
    {
        try {
            Log::info('Invoice generation service called', [
                'order_id' => $order->id,
                'order_number' => $order->number
            ]);

            // Only load relationships if not already loaded
            $relationshipsToLoad = [];
            
            if (!$order->relationLoaded('user')) {
                $relationshipsToLoad[] = 'user';
            }
            if (!$order->relationLoaded('car')) {
                $relationshipsToLoad[] = 'car';
            }
            if (!$order->relationLoaded('choices')) {
                $relationshipsToLoad[] = 'choices';
            }
            if (!$order->relationLoaded('service')) {
                $relationshipsToLoad[] = 'service';
            }
            if (!$order->relationLoaded('userPackage')) {
                $relationshipsToLoad[] = 'userPackage.package';
            }

            if (!empty($relationshipsToLoad)) {
                Log::info('Loading missing relationships', [
                    'order_id' => $order->id,
                    'relationships' => $relationshipsToLoad
                ]);
                $order->load($relationshipsToLoad);
            }

            $invoicePath = $this->generateInvoicePDF($order);

            // Check if invoice was generated
            if ($invoicePath) {
                $order->update(['invoice_url' => $invoicePath]);

                Log::info('Invoice generated and saved to order', [
                    'order_id' => $order->id,
                    'invoice_path' => $invoicePath
                ]);

                return $invoicePath;
            } else {
                Log::warning('Invoice generation returned null from Helper', [
                    'order_id' => $order->id
                ]);
                return null;
            }

        } catch (\Exception $e) {
            Log::error('Invoice generation service failed', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            // Return null on failure - invoice can be generated later
            return null;
        }
    }

    /**
     * Check if invoice generation should be attempted
     */
    public function shouldGenerateInvoice(Order $order): bool
    {
        // Don't generate if already exists
        if ($order->invoice_url) {
            Log::info('Invoice already exists', [
                'order_id' => $order->id,
                'invoice_url' => $order->invoice_url
            ]);
            return false;
        }

        return true;
    }
}
