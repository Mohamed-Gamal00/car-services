<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Color;
use App\Models\Company;
use App\Models\MainCategory;
use App\Models\MainCategorySetting;
use App\Models\Product;
use App\Models\SubSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoriesController extends Controller
{

    public function index($slug, Request $request)
    {

        if ($request->category_id == 'all_categories') {
            return redirect()->route('front.products', $request->all());
        }

        $id = MainCategory::select('id')->where('slug', $slug)->value('id');
        $query = Product::query();

        // $categoryName = isset(request()->category_id) ? MainCategory::where('id', request()->category_id)->first() : MainCategory::where('id', $id)->first();
        // if (request()->category_id) {
        //   $categoryName = MainCategory::where('id', request()->category_id)->pluck('name')->first();
        // } else {
        //   $categoryName = MainCategory::where('id', $id)->pluck('name')->first();
        // }

        // dd($categoryName);

        $categories = MainCategory::has('products')
            ->withCount(['products' => function ($query) {
                $query->where('status', 'active');
            }])->get(['id', 'name', 'slug']);

        /* الماركات */
        $companies = Company::whereHas('products', function ($query) use ($id) {
            $query->where('category_id', $id);
        })
            ->withCount(['products' => function ($query) {
                $query->where('status', 'active');
            }])
            ->get();

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


        $products = $query
            ->where('category_id', $id)
            ->where('status', 'active')->latest()->paginate();


        $categoryName = isset(request()->category_id) ? MainCategory::where('id', request()->category_id)->first() : MainCategory::where('id', $id)->first();

        return view('front.categories.index', \compact(
            'products',
            'categories',
            'companies',
            'categoryName',
        ));
    }

    public function viewAllCategories()
    {
        $categories = MainCategory::has('products')->get();

        return view("front.categories.all_categories", \compact('categories'));
    }
}
