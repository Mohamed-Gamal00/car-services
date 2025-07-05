<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TopSellingProductsController extends Controller
{
  public function index()
  {

    $topSellingProducts = Product::select('products.*', DB::raw('SUM(order_items.quantity) as total_quantity_sold'))
      ->join('order_items', 'products.id', '=', 'order_items.product_id')
      ->groupBy('products.id')
      ->orderByDesc('total_quantity_sold')
      ->limit(50)
      ->paginate();
    return view('front.top_selling.index', compact('topSellingProducts'));
  }
}
