<?php

namespace App\Http\Services\Checkout;

use App\Models\Order;
use App\Models\Service;
use Illuminate\Support\Facades\Log;

/**
 * Service for handling service-based checkout
 */
class ServiceCheckoutService
{
    protected OrderCreationService $orderCreationService;

    public function __construct(OrderCreationService $orderCreationService)
    {
        $this->orderCreationService = $orderCreationService;
    }

    /**
     * Process service-based checkout
     */
    public function process($request, $serviceId, $user): Order
    {
        Log::info('Service checkout started', [
            'user_id' => $user->id,
            'service_id' => $serviceId
        ]);

        if (!$serviceId) {
            throw new \Exception('يجب تحديد service_id', 422);
        }

        $service = Service::findOrFail($serviceId);

        // Calculate total price
        $orderTotalPrice = $this->orderCreationService->calculateTotalPrice($service, $request);

        // Validate booking time
        $this->orderCreationService->validateBookingTime($request);

        // Create order
        $order = $this->orderCreationService->createServiceOrder(
            $request,
            $service,
            $orderTotalPrice,
            $user
        );

        Log::info('Service checkout completed', ['order_id' => $order->id]);

        return $order;
    }
}
