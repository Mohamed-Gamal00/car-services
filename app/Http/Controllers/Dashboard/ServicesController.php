<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\ServiceRequest;
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
        Gate::authorize('service.view');
        $services = Service::latest()->paginate(15);

        return view('dashboard.services.index', compact('services'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('service.create');
        
        return view('dashboard.services.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ServiceRequest $request)
    {
        Gate::authorize('service.create');
        $data = $request->validated();

        // Upload main image
        if ($request->hasFile('image')) {
            $data['image'] = $this->uploadedImage($request, 'image', 'services');
        }

        // Upload icon if provided
        if ($request->hasFile('icon')) {
            $data['icon'] = $this->uploadedImage($request, 'icon', 'services/icons');
        }

        Service::create($data);

        return redirect()->route('services.index')
            ->with('success', 'تم إنشاء الخدمة بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        Gate::authorize('service.view');
        $service = Service::findOrFail($id);
        
        return view('dashboard.services.show', compact('service'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        Gate::authorize('service.edit');
        $service = Service::findOrFail($id);

        return view('dashboard.services.edit', compact('service'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ServiceRequest $request, string $id)
    {
        Gate::authorize('service.edit');
        
        $service = Service::findOrFail($id);
        $data = $request->validated();

        // Upload new main image if provided
        if ($request->hasFile('image')) {
            // Delete old image
            if ($service->image) {
                Storage::disk('public')->delete($service->image);
            }
            $data['image'] = $this->uploadedImage($request, 'image', 'services');
        }

        // Upload new icon if provided
        if ($request->hasFile('icon')) {
            // Delete old icon
            if ($service->icon) {
                Storage::disk('public')->delete($service->icon);
            }
            $data['icon'] = $this->uploadedImage($request, 'icon', 'services/icons');
        }

        $service->update($data);

        return redirect()->route('services.index')
            ->with('success', 'تم تحديث الخدمة بنجاح');
    }

    /**
     * Remove the specified resource from storage (soft delete).
     */
    public function destroy(string $id)
    {
        Gate::authorize('service.delete');

        $service = Service::findOrFail($id);
        
        // Soft delete (keeps images for potential restore)
        $service->delete();

        return redirect()->route('services.index')
            ->with('dark', 'تم حذف الخدمة بنجاح');
    }

    /**
     * Display trashed services.
     */
    public function trash()
    {
        Gate::authorize('service.trash.view');

        $services = Service::onlyTrashed()->latest()->paginate(15);
        return view('dashboard.services.trash', compact('services'));
    }

    /**
     * Restore a soft-deleted service.
     */
    public function restore(Request $request, $id)
    {
        Gate::authorize('service.restore');
        
        $service = Service::onlyTrashed()->findOrFail($id);
        $service->restore();
        
        return redirect()->back()
            ->with('success', 'تم استعادة الخدمة بنجاح');
    }

    /**
     * Permanently delete a service.
     */
    public function forceDelete($id)
    {
        Gate::authorize('service.delete.forever');

        $service = Service::onlyTrashed()->findOrFail($id);
        
        // Delete images permanently
        if ($service->image) {
            Storage::disk('public')->delete($service->image);
        }
        
        if ($service->icon) {
            Storage::disk('public')->delete($service->icon);
        }
        
        $service->forceDelete();

        return redirect()->back()
            ->with('dark', 'تم حذف الخدمة نهائياً');
    }
}
