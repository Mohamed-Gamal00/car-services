<?php

namespace App\Repositories\Package;

use App\Helper\Helper;
use App\Models\Package;
use App\Models\PackageFeature;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PackageRepository implements PackageInterface
{
    use Helper;

    protected $model;

    public function __construct(Package $package)
    {
        $this->model = $package;
    }

    public function index()
    {
        return $this->model->latest()->paginate();
    }

    public function store($data)
    {
        return DB::transaction(function () use ($data) {
            $package = $this->model->create($data);
            if (request()->has('package_features')) {
                foreach (request()->package_features as $feature) {
                    if (!empty($feature['feature'])) {
                        PackageFeature::create([
                            'feature' => $feature['feature'],
                            'feature_en' => $feature['feature_en'],
                            'package_id' => $package->id
                        ]);
                    }
                }
            }
            return $package;
        });
    }


    public function update($data, $id)
    {
        $package = Package::with('features')->findOrFail($id);

        $oldIcon = $package->icon;
        $newIcon = $this->uploadedImage(request(), 'icon', 'packages');
        if ($newIcon) {
            $data['icon'] = $newIcon;
        }
        if ($newIcon && $oldIcon) {
            Storage::disk('public')->delete($oldIcon);
        }

        $oldImage = $package->image;
        $newBgImage = $this->uploadedImage(request(), 'image', 'packages');

        if ($newBgImage) {
            $data['image'] = $newBgImage;
        }
        if ($newBgImage && $oldImage) {
            Storage::disk('public')->delete($oldImage);
        }

        $old_image_en = $package->image_en;
        $new_image_en = $this->uploadedImage(request(), 'image_en', 'packages');

        if ($new_image_en) {
            $data['image_en'] = $new_image_en;
        }
        if ($new_image_en && $old_image_en) {
            Storage::disk('public')->delete($old_image_en);
        }

        return DB::transaction(function () use ($data, $package) {

            $package->update($data);

            $requestFeatureIds = [];
            if (request()->package_features) {
                foreach (request()->package_features as $feature) {
                    if (!empty($feature['feature'])) {
                        PackageFeature::updateOrCreate(
                            ['id' => $feature['feature_id']],
                            [
                                'feature' => $feature['feature'],
                                'feature_en' => $feature['feature_en'],
                                'package_id' => $package->id
                            ]
                        );
                    }
                    $requestFeatureIds[] = $feature['feature_delete'] ?? null;
                }
            }

            $dbFeatureIds = PackageFeature::where('package_id', $package->id)->pluck('id')->toArray();
            $idsToDelete = array_diff_key($dbFeatureIds, $requestFeatureIds);

            if ($idsToDelete) {
                PackageFeature::whereIn('id', $idsToDelete)->delete();
            };
            return $package;
        });
    }

    public function delete($id)
    {
        return DB::transaction(function () use ($id) {
            $package = Package::findOrFail($id);

            // حذف صورة العربي لو موجودة
            if ($package->image && Storage::disk('public')->exists($package->image)) {
                Storage::disk('public')->delete($package->image);
            }

            // حذف صورة الإنجليزي لو موجودة
            if ($package->image_en && Storage::disk('public')->exists($package->image_en)) {
                Storage::disk('public')->delete($package->image_en);
            }

            // حذف الباقة نفسها
            $package->delete();

            return $package;
        });
    }

}