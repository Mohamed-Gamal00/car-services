<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Design;
use App\Models\HeaderBanner;
use App\Models\HeaderText;
use App\Models\MainCategory;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

# it is not necessary and it could occurred issues if the character is not encoding correctly in DB,
# also your are using here multiple quires and it's not the best practice for performance...just use single query
# Example:
# $bannerTitles = ['banner1', 'banner2'....etc];
# $designs = Design::latest()->whereIn('title', $bannerTitles)->get();
# $designsByTitle = $designs->keyBy('title');
class HomeController extends Controller
{
    public function index()
    {
        // $headerText = HeaderText::all();
        $headerImages = HeaderBanner::select('id', 'header_image', 'image_link')->get();
        $categories = MainCategory::has('products')->take(9)->get();
        // $products = Product::latest()->where('status', 'active', 'availability')->take(4)->get();
        $products = Product::latest()->take(4)->get();


        $designs = Design::all();
        $banners_result = [];

        foreach ($designs as $item) {
            if (!array_key_exists($item->name, $banners_result)) {
                $banners_result[$item->title] = $item->image;
            }
        }

        $specialProducts = $products->where('is_special', true)->take(8);
        $OffersProducts = Product::latest()->whereNotNull('discount_price')->take(4)->get();
        $mainDesign = Setting::where('id', 1)->first();
        $mainBanner = Design::where('main_banar', true)->first();

        $companies = Company::where('image', '!=', null)->take(6)->get();

        // $topSellingProducts = Product::select('products.*', DB::raw('SUM(order_items.quantity) as total_quantity_sold'))
        //   ->join('order_items', 'products.id', '=', 'order_items.product_id')
        //   ->groupBy('products.id')
        //   ->orderByDesc('total_quantity_sold')
        //   ->limit(8)
        //   ->get();

        $topSellingProducts = Product::select('products.*', DB::raw('SUM(order_items.quantity) as total_quantity_sold'))
            ->join('order_items', 'products.id', '=', 'order_items.product_id')
            ->groupBy('products.id')
            ->orderByDesc('total_quantity_sold')
            ->limit(8)
            ->take(4)
            ->get();

        return view('front.home.home', \compact(
            'headerImages',
            'banners_result',
            'categories',
            'products',
            'designs',
            'mainDesign',
            'specialProducts',
            'companies',
            'mainBanner',
            'topSellingProducts',
            'OffersProducts'
        ));
    }

    public function allProducts()
    {
        $products = Product::select('name')->withoutTrashed()->where('status', 'active')->take(10)->get();

        $data = [];
        foreach ($products as $product) {
            $data[] = $product['name'];
        }
        return response()->json($data);
    }

    // public function searchProduct(Request $request)
    // {
    //   if ($request->product_name != null) {
    //     $product = Product::where('name', 'LIKE', "%{$request->product_name}%")->first();
    //     if ($product) {
    //       return redirect('product/' . $product->slug);
    //     }
    //     else {
    //       return redirect()->back();
    //     }
    //   }
    // }


    public function searchProduct(Request $request)
    {
        // Check if the product_name parameter is not null or empty
        if ($request->has('product_name') && !empty($request->product_name)) {
            $product = Product::where('name', 'LIKE', "%{$request->product_name}%")->first();

            // If the product is found, redirect to the product's page
            if ($product) {
                return redirect('product/' . $product->slug);
            } else {
                // If no matching product is found, redirect back with an error message
                return redirect()->back()->with('error', 'No products found.');
            }
        }

        // If the product_name parameter is null or empty, redirect back with an error message
        return redirect()->back()->with('error', 'Please enter a product name.');
    }
}
