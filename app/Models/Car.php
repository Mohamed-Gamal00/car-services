<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;

    protected $fillable = [
        'brand_ar',
        'brand_en',
        'model_ar',
        'model_en',
        'year',
        'color',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relationships
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_cars')
            ->withPivot('car_model', 'car_number', 'is_default')
            ->withTimestamps();
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByBrand($query, $brand)
    {
        return $query->where('brand_ar', $brand)
            ->orWhere('brand_en', $brand);
    }

    // Helper methods
    public function getBrandAttribute()
    {
        return app()->getLocale() === 'ar' ? $this->brand_ar : $this->brand_en;
    }

    public function getModelAttribute()
    {
        return app()->getLocale() === 'ar' ? $this->model_ar : $this->model_en;
    }

    public function getCurrentNameAttribute()
    {
        return trim($this->brand . ' ' . $this->model . ' ' . $this->year);
    }
}