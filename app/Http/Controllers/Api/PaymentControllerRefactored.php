<?php

namespace App\Http\Controllers\Api;

use App\Helper\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Services\Payment\PaymentCallbackService;
use App\Http\Services\Payment\PaymentPageService;
use App\Models\Order;
use App\Models\Package;
use App\Models\Payment;
use App\Models\UserPackage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Refactored Payment Controller
 * Handles order payment page display and payment callbacks
 */
class PaymentControllerRefactored extends Controller
{
    public function __construct(
        protected PaymentPageService $paymentPageService,
        protected PaymentCallbackService $paymentCallbackService
    ) {}

    /**
     * Display payment page for order
     * 
     * @param string $order_number Order number
     * @param string $method Payment method (creditcard, mada, applepay)
     * @return \Illuminate\View\View
     */
    public function index($order_number, $method)
    {
        $order = Order::where('number', $order_number)->first();

        if (!$order) {
            abort(404, 'Order not found');
        }

        // Validate payment method
        if (!$this->paymentPageService->isValidPaymentMethod($method)) {
            abort(404, 'Invalid payment method.');
        }

        // Prepare payment page data
        $data = $this->paymentPageService->preparePaymentPageData($order, $method);

        return view('client.client-payment', $data);
    }

    /**
     * Handle payment callback from Moyasar
     * 
     * @param string $number Order number
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function callback($number)
    {
        $order = Order::where('number', $number)->first();

        if (!$order) {
            return view('client.payment-result', [
                'status' => 'error',
                'message' => __('general.Order_not_found')
            ]);
        }

        $paymentId = request()->query('id');

        if (!$paymentId) {
            return view('client.payment-result', [
                'status' => 'error',
                'message' => __('general.payment_error')
            ]);
        }

        // Process callback through service
        $result = $this->paymentCallbackService->processCallback($order, $paymentId);

        // Handle redirect if needed
        if (isset($result['redirect'])) {
            return redirect($result['redirect'])
                ->with('danger', $result['message']);
        }

        // Return result view
        return view('client.payment-result', $result);
    }

    // Keep package payment methods as they are for now
    // These can be refactored similarly in a future iteration
    
    public function package_payment_index($package_id, $method)
    {
        $package = Package::where('id', $package_id)->first();
        
        if (!$package) {
            return 'this package not found';
        }
        
        if (!$this->paymentPageService->isValidPaymentMethod($method)) {
            abort(404, 'Invalid payment method.');
        }
        
        $publishable_key = $this->paymentPageService->getPublishableKey();
        $reference = request()->query('ref');
        $paymentNetworks = $this->paymentPageService->getPaymentNetworks($method);
        $message = '';
        
        return view('client.package-subscribe-payment', compact('package', 'publishable_key', 'paymentNetworks', 'reference', 'message', 'method'));
    }

    public function package_callback($package_id)
    {
        Log::info('Package callback started', [
            'package_id' => $package_id,
            'query' => request()->all()
        ]);

        $package = Package::findOrFail($package_id);
        $id = request()->query('id');
        
        Log::info('Payment ID received', ['id' => $id]);

        $secret_key = \App\Models\Setting::pluck('secret_key')->first();
        $token = base64_encode($secret_key . ':');

        $payment = Http::baseUrl('https://api.moyasar.com/v1')
            ->withHeaders(['Authorization' => "Basic {$token}"])
            ->get("payments/{$id}")
            ->json();

        Log::info('Payment response', ['payment' => $payment]);

        $reference = $payment['metadata']['reference'] ?? null;

        if (!$reference) {
            Log::warning('Reference is missing', ['payment' => $payment]);
            return ApiResponse::sendResponse(400, 'Invalid reference');
        }

        if (isset($payment['type']) && $payment['type'] === 'authentication_error') {
            Log::error('Authentication error from Moyasar', ['payment' => $payment]);
            return redirect()->route('user.payment_package', [$package->id])
                ->with('danger', __('general.Invalid_authorization_credentials'));
        }

        if ($payment['status'] === 'paid') {
            Log::info('Payment is paid', ['payment_id' => $payment['id']]);

            $existingPayment = Payment::where('payment_id', $payment['id'])->first();

            if ($existingPayment && $existingPayment->status === 'paid') {
                Log::info('Duplicate payment detected', ['payment_id' => $payment['id']]);
                return ApiResponse::sendResponse(200, __('messages.PaidPayment'));
            }

            $userPackage = UserPackage::where('reference', $reference)
                ->where('status', 'inactive')
                ->first();

            if (!$userPackage) {
                Log::warning('User package not found or already active', ['reference' => $reference]);
                return redirect()->route('user.payment_package', [
                    'package_id' => $package->id,
                    'method' => $payment['source']['type'] ?? 'unknown'
                ])->with('danger', __('general.package_not_found_or_already_active'));
            }

            $userPackage->update([
                'status' => 'active',
                'start_date' => now(),
                'expiry_date' => now()->addDays($package->validity_days),
            ]);

            Log::info('User package activated', ['user_package_id' => $userPackage->id]);

            $paymentData = [
                'user_id' => $userPackage->user_id,
                'user_name' => $userPackage->user->first_name . ' ' . $userPackage->user->family_name,
                'service_reference' => $userPackage->reference,
                'order_number' => null,
                'package_reference' => $userPackage->reference,
                'status' => $payment['status'],
                'source' => $payment['source']['company'] ?? 'unknown',
                'payment_id' => $payment['id'],
                'cur' => $payment['currency'],
                'amount' => $payment['amount'],
                'description' => $payment['description'] ?? null,
            ];

            Log::info('Sending payment to service', ['data' => $paymentData]);

            $paymentService = app(\App\Http\Services\Payment\PaymentService::class);
            $result = $paymentService->processPayment($paymentData);

            Log::info('Payment service result', ['result' => $result]);

            if ($result === 'already_paid') {
                Log::info('Payment already processed in service');
                return ApiResponse::sendResponse(200, __('messages.PaidPayment'));
            }

            return ApiResponse::sendResponse(200, 'success');

        } elseif ($payment['status'] === 'failed') {
            Log::warning('Payment failed', ['payment' => $payment]);

            $userPackage = UserPackage::where('reference', $reference)->first();

            if ($userPackage) {
                $userPackage->update(['status' => 'payment_failed']);
                Log::info('User package marked as failed', ['user_package_id' => $userPackage->id]);
            }

            return ApiResponse::sendResponse(400, 'faild');
        }

        Log::warning('Unhandled payment status', ['status' => $payment['status'] ?? null]);
        return ApiResponse::sendResponse(400, 'faild');
    }

    public function renewal_package_payment_index($package_id, $method)
    {
        $package = Package::where('id', $package_id)->first();
        
        if (!$package) {
            return 'this package not found';
        }
        
        if (!$this->paymentPageService->isValidPaymentMethod($method)) {
            abort(404, 'Invalid payment method.');
        }
        
        $publishable_key = $this->paymentPageService->getPublishableKey();
        $reference = request()->query('ref');
        $paymentNetworks = $this->paymentPageService->getPaymentNetworks($method);
        $message = '';
        
        return view('client.package-renewal-subscribe', compact('package', 'publishable_key', 'paymentNetworks', 'reference', 'message', 'method'));
    }

    public function renewal_package_callback($package_id)
    {
        $package = Package::findOrFail($package_id);
        $id = request()->query('id');
        
        $secret_key = \App\Models\Setting::pluck('secret_key')->first();
        $token = base64_encode($secret_key . ':');

        $payment = Http::baseUrl('https://api.moyasar.com/v1')
            ->withHeaders(['Authorization' => "Basic {$token}"])
            ->get("payments/{$id}")
            ->json();

        $reference = $payment['metadata']['reference'];
        
        if (!$reference) {
            return ApiResponse::sendResponse(400, 'Invalid reference');
        }

        if (isset($payment['type']) && $payment['type'] === 'authentication_error') {
            return redirect()->route('user.renewal_payment_package', [$package->id])
                ->with('danger', __('general.Invalid_authorization_credentials'));
        }

        if ($payment['status'] === 'paid') {
            $existingPayment = Payment::where('payment_id', $payment['id'])->first();
            
            if ($existingPayment && $existingPayment->status === 'paid') {
                return ApiResponse::sendResponse(200, __('messages.PaidPayment'));
            }

            $userPackage = UserPackage::where('reference', $reference)
                ->where('status', 'inactive')
                ->first();

            if (!$userPackage) {
                return redirect()->route('user.renewal_payment_package', [
                    'package_id' => $package->id,
                    'method' => $payment['source']['type'] ?? 'unknown'
                ])->with('danger', __('general.package_not_found_or_already_active'));
            }

            Log::info('cancel exist package');
            UserPackage::where('user_id', $userPackage->user_id)
                ->where('status', 'active')
                ->Orwhere('status', 'used_up')
                ->Orwhere('status', 'expired')
                ->update(['status' => 'canceled']);
            Log::info('end cancel exist package');

            $userPackage->update([
                'status' => 'active',
                'start_date' => now(),
                'expiry_date' => now()->addDays($package->validity_in_days),
            ]);

            $paymentData = [
                'user_id' => $userPackage->user_id,
                'user_name' => $userPackage->user->first_name . ' ' . $userPackage->user->family_name,
                'order_number' => null,
                'package_reference' => $userPackage->reference,
                'status' => $payment['status'],
                'source' => $payment['source']['company'] ?? 'unknown',
                'payment_id' => $payment['id'],
                'cur' => $payment['currency'],
                'amount' => $payment['amount'],
                'description' => $payment['description'] ?? null,
            ];

            $paymentService = app(\App\Http\Services\Payment\PaymentService::class);
            $result = $paymentService->processPayment($paymentData);
            
            if ($result === 'already_paid') {
                return ApiResponse::sendResponse(200, __('messages.PaidPayment'));
            }
            
            return ApiResponse::sendResponse(200, 'success');

        } elseif ($payment['status'] === 'failed') {
            $userPackage = UserPackage::where('reference', $reference)->first();
            
            if ($userPackage) {
                $userPackage->update(['status' => 'payment_failed']);
            }
            
            return ApiResponse::sendResponse(400, 'faild');
        }

        return ApiResponse::sendResponse(400, 'faild');
    }
}
