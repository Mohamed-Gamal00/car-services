<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\CarRequest;
use App\Models\Car;
use App\Repositories\Car\CarRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CarsController extends Controller
{
    protected CarRepository $carRepository;

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
    public function store(CarRequest $request)
    {
        Gate::authorize('car.create');

        $validated = $request->validated();
        $validated['is_active'] = $request->has('is_active') ? 1 : 0;

        $this->carRepository->store($validated);

        return redirect()->route('cars.index')->with('success', 'تم إضافة السيارة بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        Gate::authorize('car.view');
        $car = $this->carRepository->getById($id);
        return view('dashboard.cars.show', compact('car'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        Gate::authorize('car.edit');
        $car = $this->carRepository->getById($id);
        return view('dashboard.cars.edit', compact('car'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CarRequest $request, string $id)
    {
        Gate::authorize('car.edit');

        $validated = $request->validated();
        $validated['is_active'] = $request->has('is_active') ? 1 : 0;

        $this->carRepository->update($validated, $id);

        return redirect()->route('cars.index')->with('success', 'تم تحديث بيانات السيارة بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Gate::authorize('car.delete');
        
        $this->carRepository->delete($id);

        return redirect()->route('cars.index')->with('success', 'تم حذف السيارة بنجاح');
    }
}
