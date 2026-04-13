<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'name_en',
        'description',
        'description_en',
        'price',
        'duration',
        'image',
        'icon',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function discountCodes()
    {
        return $this->belongsToMany(DiscountCode::class, 'discount_code_services');
    }

    // Accessors
    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return asset('assets/images/no-image.jpg');
        }
        return asset('storage/' . $this->image);
    }

    public function getIconUrlAttribute()
    {
        if (!$this->icon) {
            return null;
        }
        return asset('storage/' . $this->icon);
    }

    public function getCurrentNameAttribute()
    {
        $locale = app()->getLocale();
        return ($locale === 'en' && $this->name_en) ? $this->name_en : $this->name;
    }

    public function getCurrentDescriptionAttribute()
    {
        $locale = app()->getLocale();
        return ($locale === 'en' && $this->description_en) ? $this->description_en : $this->description;
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    // Helper methods
    public function getDurationInMinutes()
    {
        $time = explode(':', $this->duration);
        return ($time[0] * 60) + $time[1];
    }
}