<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Design;
use App\Models\Product;
use Illuminate\Http\Request;

class OfferProductController extends Controller
{
  public function index()
  {
    $products = Product::latest()->whereNotNull('discount_price')->paginate();
    $banner1 = Design::latest()->where('id', 19)->first();
    $banner2 = Design::latest()->where('id', 20)->first();
    return view('front.offer_products.index', compact('products', 'banner1', 'banner2'));
  }
}
