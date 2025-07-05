<?php

namespace App\Http\Controllers\Front;

use App\Events\OrderCreated;
use App\Http\Controllers\Controller;
use App\Http\Requests\Checkout\CheckoutRequest;
use App\Models\Admin;
use App\Models\Cart;
use App\Models\City;
use App\Models\DiscountCode;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatus;
use App\Models\Product;
use App\Models\SendNewsToUser;
use App\Models\ShippingTypesAndPrice;
use App\Models\User;
use App\Models\UserAddress;
use App\Notifications\OrderCreatedEmailAdmin;
use App\Notifications\OrderCreatedNotification;
use App\Notifications\SendOrderCreatedToUser;
use App\Repositories\Cart\CartRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Symfony\Component\Intl\Countries;
use Throwable;

class CheckoutController extends Controller
{
    public function store(CheckoutRequest $request, CartRepository $cart, Order $order)

    {
        // billing for guest & shipping for authenticated user

        if (Auth::guard('web')->check()) {
            $request->validate([
                'user_address' => 'required',
                'terms' => 'required',
            ], [
                'user_address.required' => 'برجاء اختيار عنوان او اضافة عنون جديد',
            ]);
        }

        $request->validated();


//        dd($request->all());


        $items = $cart->get();
        // dd($items);

        if ($request->payment_method == 'card_payment') {

            DB::beginTransaction();
            try {

                // store email to News if use checked join_news radio
                if ($request->join_news) {
                    $email = Auth::guard('web')->check() ? Auth::guard('web')->user()->email : $request->guest_email;

                    $existingEmail = SendNewsToUser::where('email', $email)->first();

                    if (!$existingEmail) {
                        SendNewsToUser::create(['email' => $email]);
                    }
                }

                $order = Order::create([
                    'user_id' => Auth::guard('web')->user()->id ?? null,
                    'payment_method' => $request->payment_method,
                    'order_status_id' => OrderStatus::select('id')->where('default_status', true)->first()->id,
                    'note' => $request->note,
                    'shipping_price' => request()->shipping == 'noShipping' ? null : $request->shipping_price,
                    'totalBeforeDiscount' => $cart->totalBeforeDiscount(),
                    'total_price' => $cart->total(),
                    'cookie_id' => Auth::guard('web')->check() ? null : Cart::getCookieId()
                ]);


                // dd($items);
                /* items of cart items */
                foreach ($items as $cart_items) {
                    // العناصر اللي في السلة هعمل بيها اوردر
                    $item = OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $cart_items->product_id,
                        'product_name' => $cart_items->product->name, // product is the relation
                        'price' => $cart_items->product->price * $cart_items->quantity, // product is the relation
                        'quantity' => $cart_items->quantity,
                        'color' => $cart_items->color_id
                    ]);

                    $product = Product::where('id', $item->product_id)->first();
                    $decrementQuantity = Product::where('id', $item->product_id)->update([
                        'quantity' => $product->quantity - $item->quantity
                    ]);


                    if (!empty($request->post('addr')['billing']['first_name'])) {

                        foreach ($request->post('addr') as $type => $address) {
                            $address['type'] = $type;
                            $address['email'] = Auth::guard('web')->user()->email ?? $request->guest_email;
                            $order->addresses()->create($address);
                        }
                    } elseif ($request->user_address) {

                        $UserAddress = UserAddress::where('id', $request->user_address)
                            ->first();
                        $address['type'] = 'shipping';
                        $address['first_name'] = $UserAddress->first_name ?? $request->addr['shipping']['first_name'];
                        $address['last_name'] = $UserAddress->family_name ?? $request->addr['shipping']['last_name'];
                        $address['phone_number'] = $UserAddress->phone_number ?? $request->addr['shipping']['phone_number'];
                        $address['country_id'] = $UserAddress->country_id ?? $request->addr['shipping']['country_id'];
                        $address['city_id'] = $UserAddress->city_id ?? $request->addr['shipping']['city_id'];
                        $address['address'] = $UserAddress->address ?? $request->addr['shipping']['address'];
                        $address['email'] = Auth::guard('web')->user()->email;

                        $order->addresses()->create($address);
                    }
                }

                foreach ($items as $item) {
                    $product = Product::where('id', $item->product_id)->first();
                    if ($product->quantity == 1) {
                        $product->update([
                            'status' => 'archived'
                        ]);
                    }
                }


                $admins = Admin::all();
                Notification::send($admins, new OrderCreatedNotification($order));

                $validAdmins = $admins->filter(function ($admin) {
                    return filter_var($admin->email, FILTER_VALIDATE_EMAIL);
                });
                foreach ($validAdmins as $admin) {
                    try {
                        Notification::route('mail', $admin->email)
                            ->notify(new OrderCreatedEmailAdmin($order));
                    } catch (\Exception $e) {
                    }
                }

                $user = $order->addresses->first();

                // $user->notify(new SendOrderCreatedToUser($order));


                $cart->empty();
           
                DB::commit();
                return redirect()->route('user.payment', ['order_number' => $order['number']]);
            } catch (Throwable $e) {
                DB::rollBack();
                throw $e;
            }

        } //
        else {
            DB::beginTransaction();
            try {

                // store email to News if use checked join_news radio
                if ($request->join_news) {
                    $email = Auth::guard('web')->check() ? Auth::guard('web')->user()->email : $request->guest_email;

                    $existingEmail = SendNewsToUser::where('email', $email)->first();

                    if (!$existingEmail) {
                        SendNewsToUser::create(['email' => $email]);
                    }
                }


                $order = Order::create([
                    'user_id' => Auth::guard('web')->user()->id ?? null,
                    'payment_method' => $request->payment_method, // cash on deleviry
                    'order_status_id' => OrderStatus::select('id')->where('default_status', true)->first()->id,
                    'note' => $request->note,
                    'shipping_price' => request()->shipping == 'noShipping' ? null : $request->shipping_price,
                    'totalBeforeDiscount' => $cart->totalBeforeDiscount(),
                    'total_price' => $cart->total(),
                    'cookie_id' => Auth::guard('web')->check() ? null : Cart::getCookieId()
                ]);


                // dd($items);
                /* items of cart items */
                foreach ($items as $cart_items) {
                    // العناصر اللي في السلة هعمل بيها اوردر
                    $item = OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $cart_items->product_id,
                        'product_name' => $cart_items->product->name, // product is the relation
                        'price' => $cart_items->product->price * $cart_items->quantity, // product is the relation
                        'quantity' => $cart_items->quantity,
                        'color' => $cart_items->color_id
                    ]);

                    $product = Product::where('id', $item->product_id)->first();
                    $decrementQuantity = Product::where('id', $item->product_id)->update([
                        'quantity' => $product->quantity - $item->quantity
                    ]);


                    if (!empty($request->post('addr')['billing']['first_name'])) {

                        foreach ($request->post('addr') as $type => $address) {
                            $address['type'] = $type;
                            $address['email'] = Auth::guard('web')->user()->email ?? $request->guest_email;
                            $order->addresses()->create($address);
                        }
                    } elseif ($request->user_address) {

                        $UserAddress = UserAddress::where('id', $request->user_address)
                            ->first();
                        $address['type'] = 'shipping';
                        $address['first_name'] = $UserAddress->first_name ?? $request->addr['shipping']['first_name'];
                        $address['last_name'] = $UserAddress->family_name ?? $request->addr['shipping']['last_name'];
                        $address['phone_number'] = $UserAddress->phone_number ?? $request->addr['shipping']['phone_number'];
                        $address['country_id'] = $UserAddress->country_id ?? $request->addr['shipping']['country_id'];
                        $address['city_id'] = $UserAddress->city_id ?? $request->addr['shipping']['city_id'];
                        $address['address'] = $UserAddress->address ?? $request->addr['shipping']['address'];
                        $address['email'] = Auth::guard('web')->user()->email;

                        $order->addresses()->create($address);
                    }
                }

                foreach ($items as $item) {
                    $product = Product::where('id', $item->product_id)->first();
                    if ($product->quantity == 1) {
                        $product->update([
                            'status' => 'archived'
                        ]);
                    }
                }


                $admins = Admin::all();
                Notification::send($admins, new OrderCreatedNotification($order));

                $validAdmins = $admins->filter(function ($admin) {
                    return filter_var($admin->email, FILTER_VALIDATE_EMAIL);
                });
                foreach ($validAdmins as $admin) {
                    try {
                        Notification::route('mail', $admin->email)
                            ->notify(new OrderCreatedEmailAdmin($order));
                    } catch (\Exception $e) {
                    }
                }

                $user = $order->addresses->first();

                // $user->notify(new SendOrderCreatedToUser($order));


                $cart->empty();
                DB::commit();
            } catch (Throwable $e) {
                DB::rollBack();
                throw $e;
            }

            if (Auth::guard('web')->check()) {
                return redirect()->route('user.main.orders')->with('success', 'تم اتمام الطلب بنجاح');
            }
            return redirect()->route('guest.main.orders')->with('success', 'تم اتمام الطلب بنجاح');
        }

    }

    public function create(CartRepository $cart)
    {

        // if cart is empty
        if ($cart->get()->count() == 0) {
            return redirect()->route('front.home');
        }

        $productWeight = [];

        foreach ($cart->get() as $item) {
            /* وزن كل منتج مضروب في الكمية بتاعته اللي في السلة */
            $productWeight[] = $item->product->weight * $item->quantity;
        }
        // dd($productWeight);

        // update cart weight to be total weight of each product item
        foreach ($cart->get() as $item) {
            $item->update([
                'weight' => $item->quantity * $item->product->weight
            ]);
        }

        $countries = DB::table('countries')->where('status', 'used')->get();
        $countriesId = DB::table('countries')->where('status', 'used')->pluck('id')->toArray();
        $cities = DB::table('cities')
            ->whereIn('country_id', $countriesId)->where('status', 'used')
            ->get();

        $shipping = ShippingTypesAndPrice::where('id', 1)->first();
        // dd($shipping);


        $discount = DB::table('cookie_discount_ids')->where('cookie_id', Cart::getCookieId())->first();
        // dd($discount);

        $discountPrice = null;

        if ($discount) {
            $price = DiscountCode::where('id', $discount->discount_id)->first();
            $discountPrice = $price->price;
        }

        return view('front.checkout.index', [
            'cart' => $cart,
            'user' => Auth::guard('web')->user(),
            'countries' => $countries,
            'cities' => $cities,
            'shipping' => $shipping,
            'discountPrice' => $discountPrice,
            'productWeight' => \array_sum($productWeight)
        ]);
    }
}
