<?php

namespace App\Http\Controllers\Dashboard;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Design\DesignRequest;
use App\Models\Design;
use App\Repositories\Design\DesignRepository;
use App\Services\Design\designRepo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class DesignsController extends Controller
{
    use Helper;

    public $designRepo;

    // create constructor to bind DesignRepo
    public function __construct(DesignRepository $repo)
    {
        $this->designRepo = $repo;
    }

    /**
     * Display a listing of the resource.
     */

    public function index()
    {
        Gate::authorize('design.view');
        $home_banners = $this->designRepo->getHomeBanners();
        return view('dashboard.designs.index', compact('home_banners',));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('design.create');
        return view('dashboard.designs.create');
    }

    /*
     * Store a newly created resource in storage.
     */


    public function store(DesignRequest $request)
    {
        Gate::authorize('design.create');

        // Validate request and initialize data
        $data = $request->validated();

        // Handle Arabic image upload
        $data['header_image'] = $this->uploadedImage($request, 'header_image', 'header_images');

        // Handle English image upload
        $data['header_image_en'] = $this->uploadedImage($request, 'header_image_en', 'header_images');

//        dd($data);
        // Store the design data
        $this->designRepo->store($data);

        return to_route('designs.index')->with('success', __('messages.DESIGN_CREATED'));
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
    public function edit(string $id)
    {
        Gate::authorize('design.edit');
        $design = $this->designRepo->getById($id);
//        dd($design);
        return view('dashboard.designs.edit', compact('design'));
    }

    public function update(DesignRequest $request, string $id)
    {
        Gate::authorize('design.edit');

        $design = $this->designRepo->getById($id);
        $data = $request->all();
//        dd($data);

        // Handle Arabic image
        $oldArabicImage = $design->header_image;
        $newArabicImage = $this->uploadedImage($request, 'header_image', 'header_images');
        if ($newArabicImage) {
            $data['header_image'] = $newArabicImage;
            if ($oldArabicImage) {
                Storage::disk('public')->delete($oldArabicImage);
            }
        }

        // Handle English image
        $oldEnglishImage = $design->header_image_en;
        $newEnglishImage = $this->uploadedImage($request, 'header_image_en', 'header_images');
        if ($newEnglishImage) {
            $data['header_image_en'] = $newEnglishImage;
            if ($oldEnglishImage) {
                Storage::disk('public')->delete($oldEnglishImage);
            }
        }

        $wasChanged = $this->designRepo->update($data, $id);

        if ($wasChanged) {
            return redirect()->route('designs.index')->with('success', __('messages.DESIGN_UPDATED'));
        }
        return redirect()->back()->with('dark', __('messages.DESIGN_NOT_UPDATED'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Gate::authorize('design.delete');

        $design = $this->designRepo->getById($id);

        if ($design->header_image) {
            Storage::disk('public')->delete($design->header_image);
        }
        if ($design->header_image_en) {
            Storage::disk('public')->delete($design->header_image_en);
        }
        $this->designRepo->delete($id);

        return redirect()->back()->with('dark', 'تم حذف العنصر بنجاح');
    }
}
