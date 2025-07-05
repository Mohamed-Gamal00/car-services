<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Color;
use App\Models\Company;
use App\Models\MainCategory;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();
//        dd($request->all());
        $categories = MainCategory::has('products')
            ->withCount(['products' => function ($query) {
                $query->where('status', 'active');
            }])->get();

        $companies = Company::has('products')->withCount(['products' => function ($query) {
            $query->where('status', 'active');
        }])->get();
        // dd($companies);

        if (isset($request->category_id) && !is_null($request->category_id)) {
            if ($request->category_id !== 'all_categories') {
                $query->where('category_id', $request->category_id);
            }
        }

        if (isset($request->rate_number) && !is_null($request->rate_number)) {
            if ($request->rate_number !== 'all_rates') {
                $query->whereHas('comments', function ($query) use ($request) {
                    $query->where('rate', $request->rate_number);
                });
            }
        }

        if (isset($request->company_id) && !is_null($request->company_id)) {
            if ($request->company_id !== 'all_companies') {
                $query->where('company_id', $request->company_id);
            }
        }

        if (isset($request->price_min) && !is_null($request->price_min)) {
            $query->where('price', '>=', $request->price_min);
        }

        if (isset($request->price_max) && !is_null($request->price_max)) {
            $query->where('price', '<=', $request->price_max);
        }
        if ($request->has('price')) {
            if ($request->price === 'high_to_low') {
                $query->orderByDesc('price');
            } elseif ($request->price === 'low_to_high') {
                $query->orderBy('price');
            }
        }

        $products = $query->where('status', 'active')->latest()->paginate();

        $settings = Setting::find(1);


        return view('front.products.index', \compact(
            'products',
            'categories',
            'companies',
            'settings',
        ));
    }
}
