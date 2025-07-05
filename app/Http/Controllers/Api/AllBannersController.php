<?php

namespace App\Http\Controllers\Api;

use App\Helper\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\BannersResource;
use App\Http\Resources\HeaderBannerResource;
use App\Models\Design;
use App\Models\HeaderBanner;
use Illuminate\Http\Request;

class AllBannersController extends Controller
{

    public function headerBanners()
    {
        $allBanners = HeaderBanner::all();

        if (count($allBanners) > 0) {
            return ApiResponse::sendResponse(200, 'banners Retrieved Successfully', HeaderBannerResource::collection($allBanners));
        }

        return ApiResponse::sendResponse(200, 'No Banners To Retrieved', []);
    }
}
