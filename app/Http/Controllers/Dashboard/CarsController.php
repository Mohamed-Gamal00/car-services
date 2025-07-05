<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\City;
use App\Repositories\Car\CarRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CarsController extends Controller
{

    protected $carRepository;

    public function __construct(CarRepository $carRepository)
    {
        $this->carRepository = $carRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Gate::authorize('car.view');
        $cars = $this->carRepository->getMain();
        return view('dashboard.cars.index', compact('cars'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('car.create');
        return view('dashboard.cars.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request data
        $validated = $request->validate([
            'name_ar' => 'required|string|max:255|unique:cars,name_ar',
            'name_en' => 'nullable|string|max:255|unique:cars,name_en',
        ]);

        $this->carRepository->store($validated);

        // Redirect back with a success message
        return redirect()->route('cars.index')->with('success', 'تم الاضافة بنجاح');
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
        Gate::authorize('edit.view');
        $car = $this->carRepository->getById($id);
        return view('dashboard.cars.edit', compact('car'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $car = Car::findOrFail($id);

        // Validate the request data
        $validated = $request->validate([
            'name_ar' => 'required|string|max:255|unique:cars,name_ar,' . $car->id,
            'name_en' => 'nullable|string|max:255|unique:cars,name_en,' . $car->id,
        ]);

        // Update the city with the validated data
        $this->carRepository->update($validated, $id);

        // Redirect back with a success message
        return redirect()->route('cars.index')->with('success', 'تم تحديث البايانات');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->carRepository->delete($id);

        return redirect()->route('cars.index')->with('success', 'تم الحذف بنجاح');
    }
}
