<?php

namespace App\Http\Controllers\Front;

use App\Models\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LatestProductController extends Controller
{
  public function index()
  {
    $products = Product::latest()->paginate();
    return view('front.latest_products.index', compact('products'));
  }
}
