<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\DiscountCodeRequest;
use App\Models\DiscountCode;
use App\Models\Service;
use App\Repositories\Discount_codes\DiscountRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class DiscountCodeController extends Controller
{

    protected $discountRepository;

    public function __construct(DiscountRepository $discountRepository)
    {
        $this->discountRepository = $discountRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Gate::authorize('discount_code.view');
        $discounts = $this->discountRepository->getMainDiscountCode();
        return view('dashboard.discount_codes.index', compact('discounts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('discount_code.create');
        $services = Service::select('id', 'name')->take(10)->get();
        return view('dashboard.discount_codes.create', compact('services'));
    }


    public function searchServices(Request $request)
    {
        $search = $request->input('q'); // Get the search term

        $services = Service::select('id', 'name')
            ->where('name', 'LIKE', "%{$search}%") // Filter based on the search term
            ->take(10) // Limit to 10 results
            ->get();

        $formattedServices = $services->map(function ($service) {
            return ['id' => $service->id, 'text' => $service->name];
        });

        return response()->json($formattedServices);
    }

    /**
     * Store a newly created resource in storage.
     */


    public function store(DiscountCodeRequest $request)
    {
        Gate::authorize('discount_code.create');
        $validatedData = $request->validated();
        
        // Check if 'service_ids' is present in the request
        $serviceIds = $request->has('service_ids') ? $validatedData['service_ids'] : [];
        unset($validatedData['service_ids']);

        $discountCode = $this->discountRepository->store($validatedData);
        
        // Attach the selected services to the discount code
        if ($discountCode && !empty($serviceIds)) {
            $discountCode->services()->sync($serviceIds);
        }

        return to_route('discount_code.index')->with('success', __('messages.DISCOUNT_CODE_CREATED'));
    }


    /**
     * Update the specified resource in storage.
     */


    public function edit(string $id)
    {
        Gate::authorize('discount_code.edit');
        $services = Service::select('id', 'name')->latest()->take(10)->get();
        $discountCode = DiscountCode::findOrFail($id);

        // Get the service IDs associated with this discount code
        $discountServicesIds = $discountCode->services->pluck('id')->toArray();

        return view('dashboard.discount_codes.edit', compact('discountCode', 'services', 'discountServicesIds'));
    }

    public function update(DiscountCodeRequest $request, string $id)
    {
        Gate::authorize('discount_code.edit');

        // Validate the request data
        $data = $request->validated();

        // Extract and remove service_ids from the validated data
        $serviceIds = $data['service_ids'] ?? [];
        unset($data['service_ids']);

        // Update the discount code
        $wasChanged = $this->discountRepository->update($data, $id);

        // Update the service associations
        if (!empty($serviceIds)) {
            $this->discountRepository->syncServices($id, $serviceIds);
        }

        if ($wasChanged || !empty($serviceIds)) {
            return to_route('discount_code.index')->with('success', __('messages.DISCOUNT_CODE_UPDATED'));
        }

        return to_route('discount_code.index')->with('warning', 'لم يتم التعديل لعدم وجود أي تغيير');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Gate::authorize('discount_code.delete');

        $this->discountRepository->delete($id);
        return to_route('discount_code.index')->with('dark', __('messages.DISCOUNT_CODE_DELETED'));
    }

    public function show()
    {

    }
}
