<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReturnProductsController extends Controller
{
  public function index()
  {
    $user = Auth::guard('web')->user();
    $returnProducts = User::with('returnProducts', 'orders.products')->where('id', $user->id)->first();

    $finalOrderStatus = max(OrderStatus::pluck('arrangement')->toArray());

    $products = $returnProducts->orders()
      ->where('return_order', true)
      ->paginate(10);

    $orderStatus = OrderStatus::orderBy('arrangement')->get();


    return view('front.profile.user_return_products', compact('products', 'orderStatus'));
  }



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

    return to_route('user.main.orders')->with('success', 'تم اضافة المنتج لقائمة الارجاع');
  }
}
