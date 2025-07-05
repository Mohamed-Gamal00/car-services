<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\RepresentativesOrder;
use Illuminate\Http\Request;

class RepresentativeOrderController extends Controller
{
  public function index()
  {
    return view('front.representative_order.index');
  }

  public function storeorder(Request $request)
  {
    $request->validate([
      'name' => 'required|string|max:255',
      'phone' => 'required|string|max:255',
      'description' => 'required|string',
    ]);

    RepresentativesOrder::create($request->all());

    return redirect()->back()->with('success', 'تم الارسال بنجاح');
  }
}
