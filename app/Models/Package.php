<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Package extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'name_en', 'wash_count', 'price','duration', 'validity_in_days', 'is_active', 'image','image_en', 'icon', 'description'
    ];

    public function userPackages()
    {
        return $this->hasMany(UserPackage::class);
    }

    public function features()
    {
        return $this->hasMany(PackageFeature::class);
    }

    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return asset('assets/images/no-image.jpg');
        }

        $locale = app()->getLocale();
        if ($locale === 'ar' || empty($this->image)) {
            return asset('storage/' . $this->image);

        }
        return asset('storage/' . $this->image_en);

//        if (Str::startsWith($this->image, ['http://', 'https://'])) {
//            return $this->image;
//        }
//
//        return asset('storage/' . $this->image);
    }

    public function getIconUrlAttribute()
    {
        if (!$this->icon) {
            return asset('assets/images/no-image.jpg');
        }

        if (Str::startsWith($this->icon, ['http://', 'https://'])) {
            return $this->icon;
        }

        return asset('storage/' . $this->icon);
    }

    public function getCurrentNameLangAttribute()
    {
        $locale = app()->getLocale();
        if ($locale === 'ar' || empty($this->name_en)) {
            return $this->name;
        }
        return $this->name_en;
    }

}
