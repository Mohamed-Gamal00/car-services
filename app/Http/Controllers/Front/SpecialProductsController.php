<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class SpecialProductsController extends Controller
{
    public function index()
    {
        $specialProducts = Product::where('is_special', true)
            ->where('status', 'active')
            ->paginate();
        return view('front.special_products.index', compact('specialProducts'));
    }
}
