<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CitiesController extends Controller
{
    public function getCitiesByCountry($countryId)
    {
        $cities = DB::table('cities')->where('country_id', $countryId)->where('status', 'used')->get();
        return response()->json($cities);
    }
}
