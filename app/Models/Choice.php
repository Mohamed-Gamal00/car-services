<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Choice extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'name_en',
        'image',
        'service_price',
        'parent_id',
    ];

    public function getCurrentNameLangAttribute()
    {
        $locale = app()->getLocale();
        if ($locale === 'ar' || empty($this->name_en)) {
            return $this->name;
        }
        return $this->name_en;
    }


    public function order()
    {
        return $this->belongsToMany(Order::class, 'order_choices', 'order_id', 'choice_id');
    }

    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return asset('assets/images/quick-clean.jpg');
        }

        if (Str::startsWith($this->image, ['http://', 'https://'])) {
            return $this->image;
        }

        return asset('storage/' . $this->image);
    }
}
