<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\CommonQuestion;
use App\Models\Page;
use Illuminate\Http\Request;

class StaticPageController extends Controller
{


  public function privacy_policy()
  {
    $page = Page::where('title', 'privacy_policy')->first();
    return view('front.static_pages.privacy_policy', compact('page'));
  }
  public function shipping_policy()
  {
    $page = Page::where('title', 'Shipping_policy')->first();
    return view('front.static_pages.shipping_policy', compact('page'));
  }
  public function terms_conditions()
  {
    $page = Page::where('title', 'Terms_and_Conditions')->first();
    return view('front.static_pages.terms_conditions', compact('page'));
  }
  public function questions()
  {
    $questions = CommonQuestion::all();
    // dd($questions);
    return view('front.static_pages.questions', compact('questions'));
  }
}
