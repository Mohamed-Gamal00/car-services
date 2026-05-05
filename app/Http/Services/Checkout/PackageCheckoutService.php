<?php

namespace App\Http\Services\Checkout;

use App\Models\Captain;
use App\Models\Order;
use App\Models\Package;
use App\Models\UserPackage;
use Illuminate\Support\Facades\Log;

/**
 * Service for handling package-based checkout
 */
class PackageCheckoutService
{
    protected OrderCreationService $orderCreationService;
    protected InvoiceService $invoiceService;
    protected CaptainNotificationService $captainNotificationService;
    protected PackageManagementService $packageManagementService;

    public function __construct(
        OrderCreationService $orderCreationService,
        InvoiceService $invoiceService,
        CaptainNotificationService $captainNotificationService,
        PackageManagementService $packageManagementService
    ) {
        $this->orderCreationService = $orderCreationService;
        $this->invoiceService = $invoiceService;
        $this->captainNotificationService = $captainNotificationService;
        $this->packageManagementService = $packageManagementService;
    }

    /**
     * Process package-based checkout
     */
    public function process($request, $user): Order
    {
        Log::info('Package checkout started', ['user_id' => $user->id]);

        // Validate and get user package
        $userPackage = $this->packageManagementService->validateUserPackage($request, $user);
        $package = Package::findOrFail($userPackage->package_id);

        // Calculate total price
        $orderTotalPrice = $this->orderCreationService->calculateTotalPrice($package, $request);

        // Validate booking time
        $this->orderCreationService->validateBookingTime($request);

        // Find available captain
        $captain = $this->findAvailableCaptain();

        // Create order
        $order = $this->orderCreationService->createPackageOrder(
            $request,
            $package,
            $orderTotalPrice,
            $captain,
            $userPackage,
            $user
        );

        // Generate invoice
        $this->invoiceService->generateForOrder($order);

        // Update package usage
        $this->packageManagementService->decrementPackageUsage($userPackage);

        // Notify captain
        if ($captain) {
            $this->captainNotificationService->notifyCaptainAssignment($captain, $order, $package);
        }

        Log::info('Package checkout completed', ['order_id' => $order->id]);

        return $order;
    }

    /**
     * Find an available captain
     */
    protected function findAvailableCaptain(): ?Captain
    {
        return Captain::where('status', 'available')
            ->where('is_active', 1)
            ->first();
    }
}
