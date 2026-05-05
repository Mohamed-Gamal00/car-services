<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CheckoutRequest;
use App\Http\Services\Checkout\AdminNotificationService;
use App\Http\Services\Checkout\PackageCheckoutService;
use App\Http\Services\Checkout\ServiceCheckoutService;
use App\Http\Services\Checkout\UserDataService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Refactored Checkout Controller following SOLID principles
 */
class CheckoutControllerRefactored extends Controller
{
    protected PackageCheckoutService $packageCheckoutService;
    protected ServiceCheckoutService $serviceCheckoutService;
    protected UserDataService $userDataService;
    protected AdminNotificationService $adminNotificationService;

    public function __construct(
        PackageCheckoutService $packageCheckoutService,
        ServiceCheckoutService $serviceCheckoutService,
        UserDataService $userDataService,
        AdminNotificationService $adminNotificationService
    ) {
        $this->packageCheckoutService = $packageCheckoutService;
        $this->serviceCheckoutService = $serviceCheckoutService;
        $this->userDataService = $userDataService;
        $this->adminNotificationService = $adminNotificationService;
    }

    /**
     * Process checkout for service or package
     * 
     * @param CheckoutRequest $request
     * @param int|null $serviceId
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkout(CheckoutRequest $request, $serviceId = null)
    {
        $user = $request->user();
        $isPackage = $request->has('user_package_id');

        Log::info('Checkout initiated', [
            'user_id' => $user->id,
            'service_id' => $serviceId,
            'is_package' => $isPackage
        ]);

        DB::beginTransaction();

        try {
            // Process checkout based on type
            $order = $isPackage 
                ? $this->packageCheckoutService->process($request, $user)
                : $this->serviceCheckoutService->process($request, $serviceId, $user);


            // Save user data (car and address) if requested
            $this->userDataService->saveCarDetails($request, $user);
            $this->userDataService->saveAddressDetails($request, $user);

            // Send admin notifications (non-blocking)
            // try {
            //     $this->adminNotificationService->notifyNewOrder($order);
            // } catch (\Exception $e) {
            //     Log::error('Admin notification failed but continuing', [
            //         'order_id' => $order->id,
            //         'error' => $e->getMessage()
            //     ]);
            // }

            DB::commit();

            Log::info('Checkout completed successfully', ['order_id' => $order->id]);

            return $this->successResponse($order, $isPackage, $request->payment_method);

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Checkout failed', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return $this->errorResponse($e);
        }
    }

    /**
     * Build success response
     */
    protected function successResponse($order, bool $isPackage, $paymentMethod)
    {
        $response = [
            'status' => 'success',
            'message' => __('general.Order_creation_success'),
            'data' => [
                'order_id' => $order->id,
                'total_price' => $order->total_price,
            ]
        ];

        // Add payment URL for service orders
        if (!$isPackage) {
            $response['data']['payment_url'] = route('user.payment', [
                'order_number' => $order->number,
                'method' => $paymentMethod
            ]);
        }

        return response()->json($response, 201);
    }

    /**
     * Build error response
     */
    protected function errorResponse(\Exception $e)
    {
        $statusCode = method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500;
        $statusCode = in_array($statusCode, [400, 422, 500]) ? $statusCode : 500;

        return response()->json([
            'status' => 'failed',
            'message' => __('general.Order_creation_failed'),
            'error' => $e->getMessage()
        ], $statusCode);
    }
}
