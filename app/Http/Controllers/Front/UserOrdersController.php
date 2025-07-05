<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatus;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class UserOrdersController extends Controller
{

    public function showOrder($number)
    {
        $user = Auth::guard('web')->user();
        $order = Order::latest()->with('products', 'orderStatus', 'orderItems', 'orderItems.product')->where('user_id', $user->id)
            ->where('return_order', false)
            ->where('number', $number)
            ->first();

        if (!$order) {
            abort(Response::HTTP_NOT_FOUND);
        }

        $orderStatus = OrderStatus::orderBy('arrangement')->get();
        // dd($orderStatus);
        $finalOrderStatus = max(OrderStatus::pluck('arrangement')->toArray());
        return view('front.profile.user_orders', \compact('order', 'orderStatus', 'finalOrderStatus'));


    }

    public function destroy(Request $request)
    {
        $order = Order::where('number', $request->order_number)->firstOrFail();
        $orderItem = $order->orderItems()->where('product_id', $request->product_id)->firstOrFail();
//        dd($orderItem);

        if ($orderItem->quantity > 1) {
            $orderItem->decrement('quantity', 1);
        } else {
            $orderItem->delete();
        }
        // Recalculate the totals after the deletion
        $newTotalBeforeDiscount = $order->orderItems->sum(function ($item) {
            return $item->quantity * $item->product->discount_price ?? $item->product->price;
        });

        $newTotalPrice = $order->orderItems->sum(function ($item) {
            if ($item->product->discount_price > 0) {
                return $item->quantity * ($item->discounted_price ?? $item->product->discount_price);
            }
            return $item->quantity * ($item->discounted_price ?? $item->product->price);
        });
        // Update the order with the new totals
        $order->update([
            'totalBeforeDiscount' => $newTotalBeforeDiscount,
            'total_price' => $newTotalPrice,
        ]);
        // Check if the order has no remaining items and delete it if necessary
        if (!$order->orderItems()->exists()) {
            $order->delete();
        }
        // dd($orderItem);
        return to_route('user.main.orders')->with('danger', 'تم مسح الطلب ');
    }

    /* user_main_orders */
    public function mainOrders()
    {
        $user = Auth::guard('web')->user();
        $orders = Order::latest()->with('products', 'orderStatus', 'orderItems', 'orderItems.product')->where('user_id', $user->id)
            ->where('return_order', false)
            ->has('orderItems')
            ->paginate(6);
        // ->get();

        // foreach ($orders as $order) {
        //   return
        //   $order->orderItems->count();
        // }


        $orderStatus = OrderStatus::orderBy('arrangement')->get();


        $finalOrderStatus = max(OrderStatus::pluck('arrangement')->toArray());


        return view('front.profile.main_orders', \compact('orders', 'orderStatus', 'finalOrderStatus'));
    }
}
