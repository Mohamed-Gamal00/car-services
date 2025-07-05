<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;

    protected $fillable = ['name_ar', 'name_en'];

    public function getCurrentNameLangAttribute()
    {
        $locale = app()->getLocale();
        if ($locale === 'ar' || empty($this->name_en)) {
            return $this->name_ar;
        }
        return $this->name_en;
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_cars')
            ->withPivot('car_model')
            ->withTimestamps();
    }
}
