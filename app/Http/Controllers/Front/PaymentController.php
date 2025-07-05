<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class PaymentController extends Controller
{
    public function index($order_number)
    {
        $order = Order::where('number', $order_number)->first();

        if ($order) {
            if ($order->payment_status == 'paid') {
                // Flash message to session
                session()->flash('success', 'This order has already been paid.');
            }

            return view('front.profile.user-payment', compact('order'));
        } else {
            return "Order does not exist.";
        }
    }

    public function callback($number)
    {
        $order = Order::where('number', $number)->first();
        if ($order->payment_status == 'paid') {
            return redirect()->route('user.payment', [$order->number])->with('success', 'This order has already been paid');
        }
        if (!$order) {
            return redirect()->route('user.orders')->with('error', 'Order not found.');
        }
//        return $order;
        $id = request()->query('id');

        $token = base64_encode(config('services.moyasar.secret') . ':');

        $payment = Http::baseUrl('https://api.moyasar.com/v1')
            ->withHeaders([
                'Authorization' => "Basic {$token}",
            ])
            ->get("payments/{$id}")
            ->json();

        if ($payment['status'] === 'paid') {
            $order->payment_status = 'paid';
            $order->save();


            if (Auth::guard('web')->check()) {
                return redirect()->route('user.orders', [$order->number])->with('success', 'عملية دفع ناجحة');
            } else {
                if ($order->guest_id != null || $order->user_id != null) {
                    return response()->json(['status' => 'success', 'message' => 'Payment successful', 'order_number' => $order->number], 200);
                } elseif ($order->cookie_id != null) {
                    return redirect()->route('guest.orders', [$order->number])->with('success', 'عملية دفع ناجحة');
                } else {
                    return response()->json(['status' => 'success', 'message' => 'no action', 'order_number' => $order->number], 200);
                }
            }
        } elseif ($payment['status'] === 'failed') {
            $order->payment_status = 'failed';
            $order->save();
            if (Auth::guard('web')->check()) {
                return redirect()->route('user.orders', [$order->number])->with('success', 'فشل عملية الدفع');
            } else {
                if ($order->guest_id != null || $order->user_id != null) {
                    return response()->json(['status' => 'success', 'message' => 'Payment faild', 'order_number' => $order->number], 200);
                } elseif ($order->cookie_id != null) {
                    return redirect()->route('guest.orders', [$order->number])->with('success', 'فشل عملية الدفع');
                } else {
                    return response()->json(['status' => 'success', 'message' => 'no action', 'order_number' => $order->number], 200);
                }
            }

        } else {
            return response()->json(['status' => 'success', 'message' => 'Payment failed', 'order_number' => $order->number], 200);

        }

    }
}
