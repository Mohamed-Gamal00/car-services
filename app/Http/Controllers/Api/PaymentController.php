<?php

namespace App\Http\Controllers\Api;

use App\Helper\ApiResponse;
use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Http\Services\Payment\PaymentService;
use App\Jobs\AssignCaptainToOrder;
use App\Jobs\MakeCaptainAvailableJob;
use App\Models\Admin;
use App\Models\Captain;
use App\Models\Order;
use App\Models\Package;
use App\Models\Payment;
use App\Models\Service;
use App\Models\Setting;
use App\Models\User;
use App\Models\UserPackage;
use App\Notifications\CaptainAssignedNotification;
use App\Notifications\OrderCreatedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;


class PaymentController extends Controller

{
    use Helper;

    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function index($order_number, $method)
    {
        $order = Order::where('number', $order_number)->first();
        $publishable_key = Setting::all()->pluck('publishable_key')->first();

        // Determine available methods based on the user's choice
        $paymentNetworks = [];
        switch ($method) {
            case 'creditcard':
                $paymentNetworks = ['mastercard', 'visa'];
                break;
            case 'mada':
                $paymentNetworks = ['mada'];
                break;
            case 'applepay':
                $paymentNetworks = ['visa', 'mastercard', 'mada']; // Apple Pay يدعمها
                break;
            default:
                abort(404, 'Invalid payment method.');
        }

        $message = '';
        if ($order) {
            return view('client.client-payment', compact('order', 'publishable_key', 'paymentNetworks', 'message', 'method'));
        }
        return 'this order not found';
    }

    public function callback($number)
    {

        $order = Order::where('number', $number)->first();

        $id = request()->query('id');

        $secret_key = Setting::all()->pluck('secret_key')->first();
        $token = base64_encode($secret_key . ':');

        $payment = Http::baseUrl('https://api.moyasar.com/v1')
            ->withHeaders([
                'Authorization' => "Basic {$token}",
            ])
            ->get("payments/{$id}")
            ->json();

        if (isset($payment['type']) && $payment['type'] === 'authentication_error') {
            return redirect()->route('user.payment', [$order->number])->with('danger', __('general.Invalid_authorization_credentials'));
        }

        Log::info('payment');
        $paymentData = [
            'user_id' => $order->user_id,
            'user_name' => $order->user->first_name . ' ' . $order->user->family_name,
            'order_number' => $order->number,
            'status' => $payment['status'],
            'source' => $payment['source']['company'],
            'payment_id' => $payment['id'],
            'cur' => $payment['currency'],
            'amount' => $payment['amount'],
            'description' => $payment['description'] ?? null,
        ];
        $result = $this->paymentService->processPayment($paymentData);
        if ($result === 'already_paid') {
            return redirect()->back()->with('success', __('messages.PaidPayment'));
        }

        $service = Service::where('id', $order->service_id)->first();
        $availableCaptains = Captain::where('status', 'available')->where('is_active', 1)->get();
        if ($payment['status'] === 'paid') {
            Log::info('success payment');
            $captain = $availableCaptains->shift();
            $order->order_status_id = 3;
            $order->payment_status = 'paid';
            $order->payment_method = $payment['source']['company'] ?? null;

            Log::info('before assign captain to order');
            if ($order->booking_date === now()->toDateString()) {
                if ($captain) {
                    $captain->notify(new CaptainAssignedNotification($order));
                    Log::info('payment controller start assign captain to order');
                    dispatch(new AssignCaptainToOrder());

                }
            }
            // Generate the invoice PDF and save the URL
            Log::info('create invoice url');
            $invoicePath = $this->generateInvoicePDF($order);
            $order->update(['invoice_url' => $invoicePath]);

            $order->save();

//           Send notifications to admins


            return response()->json([
                'status' => 'success',
                'message' => 'payment successfully',
            ], 201);


        } elseif ($payment['status'] === 'failed') {
            $order->payment_status = 'failed';
            $order->save();
            return response()->json([
                'status' => 'failed',
                'message' => 'payment failed',
            ], 201);

        } else {
            return response()->json(['status' => 'error', 'message' => 'Order not found.'], 400);

        }


    }

    public function package_payment_index($package_id, $method)
    {
        $package = Package::where('id', $package_id)->first();
        $publishable_key = Setting::all()->pluck('publishable_key')->first();
        $reference = request()->query('ref');
        // Determine available methods based on the user's choice
        $paymentNetworks = [];
        switch ($method) {
            case 'creditcard':
                $paymentNetworks = ['mastercard', 'visa'];
                break;
            case 'mada':
                $paymentNetworks = ['mada'];
                break;
            case 'applepay':
                $paymentNetworks = ['visa', 'mastercard', 'mada']; // Apple Pay يدعمها
                break;
            default:
                abort(404, 'Invalid payment method.');
        }

        $message = '';
        if ($package) {
            return view('client.package-subscribe-payment', compact('package', 'publishable_key', 'paymentNetworks', 'reference', 'message', 'method'));
        }
        return 'this package not found';
    }

    public function package_callback($package_id)
    {
        $package = Package::findOrFail($package_id);

        $id = request()->query('id');
        $secret_key = Setting::pluck('secret_key')->first();
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
            return redirect()->route('user.payment_package', [$package->id])->with('danger', __('general.Invalid_authorization_credentials'));
        }

        if ($payment['status'] === 'paid') {

            $existingPayment = Payment::where('payment_id', $payment['id'])->first();
            if ($existingPayment) {
                return ApiResponse::sendResponse(200, __('messages.PaidPayment'));
            }

            Log::info('existingPayment', [
                'existingPayment' => $existingPayment,
            ]);


            $userPackage = UserPackage::where('reference', $reference)
                ->where('status', 'inactive')
                ->first();

            if (!$userPackage) {
                return redirect()->route('user.payment_package', [
                    'package_id' => $package->id,
                    'method' => $payment['source']['type'] ?? 'unknown'])->with('danger', __('general.package_not_found_or_already_active'));
            }

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

            $this->paymentService->processPayment($paymentData);
            return ApiResponse::sendResponse(200, 'success');

        } elseif ($payment['status'] === 'failed') {
            return ApiResponse::sendResponse(400, 'faild');
        }

        return ApiResponse::sendResponse(400, 'faild');
    }

    public function renewal_package_payment_index($package_id, $method)
    {
        $package = Package::where('id', $package_id)->first();
        $publishable_key = Setting::all()->pluck('publishable_key')->first();
        $reference = request()->query('ref');
        // Determine available methods based on the user's choice
        $paymentNetworks = [];
        switch ($method) {
            case 'creditcard':
                $paymentNetworks = ['mastercard', 'visa'];
                break;
            case 'mada':
                $paymentNetworks = ['mada'];
                break;
            case 'applepay':
                $paymentNetworks = ['visa', 'mastercard', 'mada']; // Apple Pay يدعمها
                break;
            default:
                abort(404, 'Invalid payment method.');
        }

        $message = '';
        if ($package) {
            return view('client.package-renewal-subscribe', compact('package', 'publishable_key', 'paymentNetworks', 'reference', 'message', 'method'));
        }
        return 'this package not found';
    }

    public function renewal_package_callback($package_id)
    {
        $package = Package::findOrFail($package_id);

        $id = request()->query('id');
        $secret_key = Setting::pluck('secret_key')->first();
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
            return redirect()->route('user.renewal_payment_package', [$package->id])->with('danger', __('general.Invalid_authorization_credentials'));
        }

        if ($payment['status'] === 'paid') {

            $existingPayment = Payment::where('payment_id', $payment['id'])->first();
            if ($existingPayment) {
                return ApiResponse::sendResponse(200, __('messages.PaidPayment'));
            }

            $userPackage = UserPackage::where('reference', $reference)
                ->where('status', 'inactive')
                ->first();

            if (!$userPackage) {
//                return ApiResponse::sendResponse(200, __('general.package_not_found_or_already_active'));
                return redirect()->route('user.renewal_payment_package', [
                    'package_id' => $package->id,
                    'method' => $payment['source']['type'] ?? 'unknown'])->with('danger', __('general.package_not_found_or_already_active'));
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

            $this->paymentService->processPayment($paymentData);
            return ApiResponse::sendResponse(200, 'success');

        } elseif ($payment['status'] === 'failed') {
            return ApiResponse::sendResponse(400, 'faild');
        }

        return ApiResponse::sendResponse(400, 'faild');
    }

}
