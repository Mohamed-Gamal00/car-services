<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductDetailsController extends Controller
{
  public function index($slug)
  {
    // parent is MainCategory
    $product = Product::with('parent', 'company', 'colors')->where('slug', $slug)->first();
    // dd($product);
    $user = Auth::guard('web')->user();
    // dd($user);
    // get User Order Products and check if current product exists in current user order products to hide create comment
    $isOrdered = false;
    if (Auth::guard('web')->check()) {

      foreach ($user->orders as $item) {

        foreach ($item->products as $currentProduct) {

          $currentProduct->id === $product->id ? $isOrdered = true : '';
        }
      }
    }

    $relatedProducts = Product::latest()->where('category_id', $product->category_id)->take(4)->get();

    return view('front.product_details.index', compact(
      'product',
      'relatedProducts',
      'isOrdered',
    ));
  }
}
