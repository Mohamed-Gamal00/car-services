<?php

namespace App\Http\Controllers\Dashboard;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Package\PackageRequest;
use App\Models\Package;
use App\Repositories\Package\PackageRepository;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    protected $packageRepository;

    use Helper;

    public function __construct(PackageRepository $Repo)

    {
        $this->packageRepository = $Repo;
    }

    public function index()
    {
        $packages = $this->packageRepository->index();
        return view('dashboard.packages.index', compact('packages',));
    }

    public function create()
    {
        return view('dashboard.packages.create');
    }

    public function store(PackageRequest $request)
    {

        $data = $request->validated();

        $data['icon'] = $this->uploadedImage(request(), 'icon', 'packages');
        $data['image'] = $this->uploadedImage(request(), 'image', 'packages');
        $data['image_en'] = $this->uploadedImage(request(), 'image_en', 'packages');



        $this->packageRepository->store($data);

        return redirect()->route('packages.index')
            ->with('success', 'تم الاضافة بنجاح');
    }

    public function edit(string $id)
    {
        $package = Package::with( 'features')->findOrFail($id);

        return view('dashboard.packages.edit', compact(
            'package',

        ));
    }

    public function update(PackageRequest $request, string $id)
    {

        $data = $request->validated();
        $this->packageRepository->update($data, $id);

        return redirect()->route('packages.index')
            ->with('success', __('messages.UPDATED'));
    }

    public function destroy(string $id)
    {


        $this->packageRepository->delete($id);

        return redirect()->route('packages.index')
            ->with('dark', __('messages.DELETED'));
    }

}
