<?php

namespace App\Console\Commands;

use App\Http\Services\Payment\InvoiceGenerationService;
use App\Models\Order;
use Illuminate\Console\Command;

class RegenerateInvoice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invoice:regenerate {order_number : The order number to regenerate invoice for}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Regenerate invoice for a specific order';

    /**
     * Execute the console command.
     */
    public function handle(InvoiceGenerationService $invoiceService)
    {
        $orderNumber = $this->argument('order_number');

        $this->info("Looking for order: {$orderNumber}");

        $order = Order::where('number', $orderNumber)->first();

        if (!$order) {
            $this->error("Order not found: {$orderNumber}");
            return 1;
        }

        $this->info("Order found: ID {$order->id}");
        $this->info("Current invoice URL: " . ($order->invoice_url ?? 'None'));

        // Load all relationships
        $this->info("Loading relationships...");
        $order->load([
            'user',
            'car',
            'choices',
            'service',
            'userPackage.package'
        ]);

        // Display relationship status
        $this->table(
            ['Relationship', 'Status'],
            [
                ['User', $order->user ? '✓ Loaded' : '✗ Missing'],
                ['Car', $order->car ? '✓ Loaded' : '✗ Missing'],
                ['Service', $order->service ? '✓ Loaded' : '✗ Missing'],
                ['Package', $order->userPackage ? '✓ Loaded' : '✗ Missing'],
                ['Choices', $order->choices->count() . ' items'],
            ]
        );

        if ($this->confirm('Do you want to regenerate the invoice?', true)) {
            $this->info("Generating invoice...");

            $invoicePath = $invoiceService->generateInvoice($order);

            if ($invoicePath) {
                $this->info("✓ Invoice generated successfully!");
                $this->info("Invoice path: {$invoicePath}");
                $this->info("Full URL: " . asset('storage/' . $invoicePath));
                return 0;
            } else {
                $this->error("✗ Invoice generation failed. Check logs for details.");
                $this->info("Run: tail -f storage/logs/laravel.log | grep invoice");
                return 1;
            }
        }

        $this->info("Invoice regeneration cancelled.");
        return 0;
    }
}
