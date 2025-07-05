<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageFeature extends Model
{
    use HasFactory;
    protected $fillable = ['package_id', 'feature','feature_en'];

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function getCurrentNameLangAttribute()
    {
        $locale = app()->getLocale();
        if ($locale === 'ar' || empty($this->feature_en)) {
            return $this->feature;
        }
        return $this->feature_en;
    }
}
