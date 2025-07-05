<?php

namespace App\Http\Controllers\Api;

use App\Helper\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\CarResource;
use App\Models\Car;
use App\Models\City;
use Illuminate\Http\Request;

class CarsController extends Controller
{
    public function allCars()
    {
        $cars = Car::all();

        if (count($cars) > 0) {
            return ApiResponse::sendResponse(200, 'cars Retrieved Successfully', CarResource::collection($cars));
        }
        return ApiResponse::sendResponse(200, 'No cars To Retrieved', []);
    }
}
