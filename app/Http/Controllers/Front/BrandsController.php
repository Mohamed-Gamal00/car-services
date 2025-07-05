<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;

class BrandsController extends Controller
{
  public function index()
  {
    $brands = Company::has('products')->get();
    // dd($brands);
    return view("front.brands.all_brands", compact('brands'));
  }
}
