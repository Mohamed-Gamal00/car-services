<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\DiscountCode;
use App\Models\UserDiscountCode;
use App\Repositories\Cart\CartRepository;
use Illuminate\Http\Request;

class CheckDiscountCode extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, CartRepository $cart)
    {
        $request->validate([
            'discount_code' => 'required|exists:discount_codes,code'
        ]);

        $discountCode = DiscountCode::where('code', $request->discount_code)
            ->where('status', 'active')
            ->where('number_of_used', '>', 0)
            ->first();

        // Debugging: Log discount code details
        if (!$discountCode) {
            return redirect()->back()->with('danger', 'عفوا انتهت صلاحية هذا الكود');
        }

        $numberOfUsed = DiscountCode::where('id', $discountCode->id)->first();

        // dd($discountCode);

        // return $userDiscountCode;
        $userDiscountCode = UserDiscountCode::where([
            'cookie_id' => Cart::getCookieId(),
            'discount_id' => $discountCode->id
        ])->first();


        if ($userDiscountCode) {
            return redirect()->back()->with('danger', 'لقد استخدمت هذا الكود بالفعل');
        } else {
            UserDiscountCode::create([
                'cookie_id' => Cart::getCookieId(),
                'discount_id' => $discountCode->id
            ]);
        }
        /* decrement number of dicount code */
        DiscountCode::where('id', $discountCode->id)->update([
            'number_of_used' => $numberOfUsed->number_of_used - 1
        ]);

        $isGlobalDiscount = $discountCode->products()->count() === 0;

        $cartItems = $cart->get();
        foreach ($cartItems as $item) {
            // موجودة في السلة $discountCode->products لو الخدمات اللي عليها خصم
            if ($isGlobalDiscount || $discountCode->products->contains($item->product_id)) {
                // Apply discount based on discount type نوع الخصم اذا كان نسبة او ثابت
                if ($discountCode->discount_type == 'percentage') {
                    $discountAmount = (($item->product->discount_price ?? $item->product->price) * $discountCode->price) / 100;
                } else {
                    $discountAmount = $discountCode->price;
                }
                // Set the discounted price
                // نسبة الخصم علي المنتج الواحد
                $originalPrice = $item->product->discount_price ?? $item->product->price;
                $item->discounted_price = max(0, $originalPrice - $discountAmount);
                $item->save();
            }
        }

        return \redirect()->back()->with('success', 'تم اضافة كود الخصم بنجاح');
    }
}
