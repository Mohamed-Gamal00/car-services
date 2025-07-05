<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\ShippingTypesAndPrice;
use App\Repositories\Cart\CartRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShippingController extends Controller
{
  public function getShippingCost($cityId, CartRepository $cart)
  {
    $city = City::findOrFail($cityId);
    $shippingCost = $city->shipping_price;

    $totalCart = $cart->total();

    $productWeight = [];

    foreach ($cart->get() as $item) {
      $productWeight[] = $item->product->weight * $item->quantity;
    }


    $shippingType = ShippingTypesAndPrice::find(1);


    if ($shippingType->add_wight_price) {
      $shippingCost = ($shippingType->weight_price * array_sum($productWeight));
    } else {
      $shippingCost = $shippingType->normal_shipping_price;
    }


    if ($shippingType->add_normal_price) {

      if ($shippingType->add_wight_price) {

        // $shippingCost = $shippingType->normal_shipping_price + ($shippingType->weight_price * array_sum($productWeight));
        $shippingCost = ($shippingType->weight_price * array_sum($productWeight));
      } else {
        $shippingCost = $shippingType->normal_shipping_price;
      }
    }


    if ($shippingType->add_price_based_on_city) {

      if ($shippingType->add_wight_price) {
        $shippingCost = $city->shipping_price + ($shippingType->weight_price * array_sum($productWeight));
      } else {
        $shippingCost = $city->shipping_price;
      }
    }


    // $formattedShippingCost = resolve('App\currency\Currency')->getCurrency($shippingCost);//  القيمة هنا بتيجي معاها 
    $formattedShippingCost = $shippingCost;

    // Update totalCart to include the shipping cost if it is a valid number
    if (isset($shippingCost) && is_numeric($shippingCost)) {
      $totalCart += $shippingCost;
    }



    return response()->json([
      'shipping_cost' => $shippingCost,
      'formatted_shipping_cost' => $formattedShippingCost,
      'total_Cart' => $totalCart,
    ]);
  }
}
