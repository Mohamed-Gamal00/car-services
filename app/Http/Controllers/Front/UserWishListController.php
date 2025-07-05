<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserWishListController extends Controller
{
    public function index()
    {
        return view('front.profile.wishlist_products');
    }
}
