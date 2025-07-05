<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Guest;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatus;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GuestOrdersController extends Controller
{
    /* مشاهدة الطلب */
    public function orders($number)
    {
        $guest_cookie_id = Cart::getCookieId();
        $order = Order::latest()->with('products', 'orderStatus', 'orderItems', 'orderItems.product')->where('cookie_id', $guest_cookie_id)
            ->where('return_order', false)
            ->where('number', $number)
            ->first();

        // return $order->products;

        $orderStatus = OrderStatus::orderBy('arrangement')->get();


        $finalOrderStatus = max(OrderStatus::pluck('arrangement')->toArray());

        return view('front.guest_orders.orders', compact('order', 'orderStatus'));
    }

    /* حذف الطلب */
    public function guestOrderDelete(Request $request)
    {
        $orderItem = OrderItem::where('product_id', $request->product_id)->first();
        $order = OrderItem::findOrFail($orderItem->id);
        $order->delete();

        $currenctOrder = Order::where('number', $request->order_number)
            ->has('orderItems')
            ->first();

        if (is_null($currenctOrder)) {
            $order = Order::where('number', $request->order_number)->first();
            $order->delete();
        }


        return to_route('guest.main.orders')->with('danger', 'تم مسح الطلب ');
    }

    /* مرتجعات */
    public function guestReturnProductsindex()
    {
        $products = Order::with('products')
            ->where('cookie_id', Cart::getCookieId())
            ->where('return_order', true)
            ->paginate(10);

        $finalOrderStatus = max(OrderStatus::pluck('arrangement')->toArray());

        $orderStatus = OrderStatus::orderBy('arrangement')->get();


        return view('front.guest_orders.guest_return_products', compact('products', 'orderStatus'));
    }

    /* ارجاع */
    public function store(Request $request)
    {
        $firstOrderStatus = min(OrderStatus::pluck('arrangement')->toArray());

        $statusId = OrderStatus::where('default_status', true)->first();

        if ($request->return_order_id) {
            Order::where('id', $request->return_order_id)->update([
                'return_order' => true,
                'order_status_id' => $statusId->id
            ]);
        }

        return to_route('guest.main.orders')->with('success', 'تم اضافة المنتج لقائمة الارجاع');
    }

    /* الطلبات */
    public function mainOrders()
    {
        $orders = Order::latest()->with('products', 'orderStatus', 'orderItems', 'orderItems.product')
            ->where('cookie_id', Cart::getCookieId())
            ->where('return_order', false)
            ->has('orderItems')
            ->paginate(6);


        $orderStatus = OrderStatus::orderBy('arrangement')->get();


        $finalOrderStatus = max(OrderStatus::pluck('arrangement')->toArray());


        return view('front.guest_orders.main_orders', \compact('orders', 'orderStatus', 'finalOrderStatus'));
    }

    /* المفضلة */
    public function guestWishList(Request $request)
    {
        $guestId = $this->getGuestId($request);
        dd($guestId);
        $guest = Guest::find($guestId);

        if ($guest) {
            $products = $guest->wishlistProducts;
            return view('front.guest_orders.guest_wishlist', compact('products'));
        }
    }

    private function getGuestId(Request $request)
    {
        $guestId = $request->cookie('guest_id');
        if (!$guestId) {
            $guestId = (string)Str::uuid();
            cookie()->queue('guest_id', $guestId, 60 * 24 * 30); // Store guest_id for 30 days
        }
        return $guestId;
    }
}
