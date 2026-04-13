<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Models\Service;
use App\Helper\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class ServicesController extends Controller
{
    use Helper;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Gate::authorize('product.view');
        $services = Service::latest()->paginate(15);

        return view('dashboard.services.index', compact('services'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('product.create');
        
        return view('dashboard.services.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request)
    {
        Gate::authorize('product.create');
        $data = $request->validated();

        $data['image'] = $this->uploadedImage(request(), 'image', 'services');
        $data['slug'] = str_replace(' ', '-', $request->name);

        Service::create($data);

        return redirect()->route('services.index')
            ->with('success', __('messages.SERVICE_CREATED'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        Gate::authorize('product.view');
        $service = Service::findOrFail($id);
        
        return view('dashboard.services.show', compact('service'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        Gate::authorize('product.edit');
        $service = Service::findOrFail($id);

        return view('dashboard.services.edit', compact('service'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductRequest $request, string $id)
    {
        Gate::authorize('product.edit');
        
        $service = Service::findOrFail($id);
        $data = $request->validated();
        $data['slug'] = str_replace(' ', '-', $request->name);

        if ($request->hasFile('image')) {
            // Delete old image
            if ($service->image) {
                Storage::disk('public')->delete($service->image);
            }
            $data['image'] = $this->uploadedImage(request(), 'image', 'services');
        }

        $service->update($data);

        return redirect()->route('services.index')
            ->with('success', __('messages.SERVICE_UPDATED'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Gate::authorize('product.delete');

        $service = Service::findOrFail($id);
        
        // Delete image if exists
        if ($service->image) {
            Storage::disk('public')->delete($service->image);
        }
        
        $service->delete();

        return redirect()->route('services.index')
            ->with('dark', __('messages.SERVICE_DELETED'));
    }

    public function trash()
    {
        Gate::authorize('product.trash.view');

        $services = Service::onlyTrashed()->paginate();
        return view('dashboard.services.trash', compact('services'));
    }

    public function restore(Request $request, $id)
    {
        Gate::authorize('product.restore');
        $service = Service::onlyTrashed()->findOrFail($id);
        $service->restore();
        return redirect()->back()
            ->with('success', __('messages.SERVICE_RESTORE'));
    }

    public function forceDelete($id)
    {
        Gate::authorize('product.delete.forever');

        $service = Service::onlyTrashed()->findOrFail($id);
        $service->forceDelete();

        if ($service->image) {
            Storage::disk('public')->delete($service->image);
        }

        return redirect()->back()
            ->with('dark', __('messages.SERVICE_REMOVED'));
    }
}
