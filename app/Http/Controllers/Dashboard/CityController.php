<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Country;
use App\Repositories\City\CityRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CityController extends Controller
{

    protected $cityRepository;

    public function __construct(CityRepository $cityRepository)
    {
        $this->cityRepository = $cityRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cities = $this->cityRepository->getMainCity();
        return view('dashboard.cities.index', compact('cities'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
//        $countries = $this->cityRepository->getCountries();
        $cities = $this->cityRepository->getNotUsedCities();
        return view('dashboard.cities.create', compact('cities'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request data
        $validated = $request->validate([
            'name_ar' => 'required|string|max:255|unique:cities,name_ar',
            'name_en' => 'nullable|string|max:255|unique:cities,name_en',
            'latitude' => ['nullable', 'regex:/^\d{1,10}(\.\d{1,15})?$/'],
            'end_latitude' => ['nullable', 'regex:/^\d{1,10}(\.\d{1,15})?$/'],
            'longitude' => ['nullable', 'regex:/^\d{1,10}(\.\d{1,15})?$/'],
            'end_longitude' => ['nullable', 'regex:/^\d{1,10}(\.\d{1,15})?$/'],
            'polygon_coordinates' => ['required'],
            'code' => 'nullable|numeric|unique:cities,code',
            'status' => 'nullable|in:used,not_used',
            'shipping_price' => 'nullable|numeric',
        ], [
            'latitude.regex' => 'The latitude must be a valid decimal number with up to 10 digits and 8 decimal places.',
            'longitude.regex' => 'The longitude must be a valid decimal number with up to 10 digits and 8 decimal places.',
        ]);

        $this->cityRepository->store($validated);

        // Redirect back with a success message
        return redirect()->route('cities.index')->with('success', __('messages.CITY_CREATED'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $city = $this->cityRepository->getById($id);
//        $countries = $this->cityRepository->getCountries();
        return view('dashboard.cities.edit', compact('city',));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $city = City::findOrFail($id);

        // Validate the request data
        $validated = $request->validate([
            'country_id' => 'nullable|exists:countries,id',
            'name_ar' => 'nullable|string|max:255|unique:cities,name_ar,' . $city->id,
            'name_en' => 'nullable|string|max:255|unique:cities,name_en,' . $city->id,
            'latitude' => ['nullable', 'regex:/^\d{1,10}(\.\d{1,15})?$/'],
            'longitude' => ['nullable', 'regex:/^\d{1,10}(\.\d{1,15})?$/'],
            'end_latitude' => ['nullable', 'regex:/^\d{1,10}(\.\d{1,15})?$/'],
            'end_longitude' => ['nullable', 'regex:/^\d{1,10}(\.\d{1,15})?$/'],
            'code' => 'nullable|numeric|unique:cities,code,' . $city->id,
            'polygon_coordinates' => ['required'],
            'status' => 'nullable|in:used,not_used',
            'shipping_price' => 'nullable|numeric',
        ]);
//        dd($validated);

        // Update the city with the validated data
        $this->cityRepository->update($validated, $id);

        // Redirect back with a success message
        return redirect()->route('cities.index')->with('success', __('messages.CITY_UPDATED'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $cityCount = City::count();

        if ($cityCount == 1) {
            // You can either return a message saying the last city cannot be deleted
            return redirect()->route('cities.index')->with('danger', ('لا يمكن حذف تلك المدينة'));
        }
        $this->cityRepository->delete($id);

        return redirect()->route('cities.index')->with('success', __('messages.CITY_DELETED'));
    }
}
