<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageFeature extends Model
{
    use HasFactory;

    protected $fillable = [
        'package_id',
        'feature',
        'feature_en',
        'icon',
        'sort_order',
    ];

    // Relationships
    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    // Accessors
    public function getCurrentFeatureAttribute()
    {
        $locale = app()->getLocale();
        return ($locale === 'en' && $this->feature_en) ? $this->feature_en : $this->feature;
    }

    public function getIconUrlAttribute()
    {
        if (!$this->icon) {
            return null;
        }
        return asset('storage/' . $this->icon);
    }

    // Scopes
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }
}