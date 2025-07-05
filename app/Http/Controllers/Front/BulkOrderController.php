<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Requests\Front\bullkorder\BulkRequest;
use App\Models\BulkOrder;
use Illuminate\Http\Request;

class BulkOrderController extends Controller
{
  public function index()
  {
    return view('front.bulk_orders.index');
  }


  public function storeorder(BulkRequest $request)
  {
    BulkOrder::create($request->all());
    return redirect()->back()->with('success', 'تم الارسال بنجاح');
  }
}
