<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'website_name',
        'website_name_en',
        'address',
        'address_en',
        'subscription_title',
        'subscription_title_en',
        'phone',
        'whatsaap',
        'image',
        'facebook',
        'twitter',
        'description',
        'email',
        'logo',
        'instagram',
        'phone_number',
        'snap',
        'tiktok',
        'tax_number',
        'value_added_tax',
        'publishable_key',
        'secret_key',
        'sms_api_key',
        'sms_user_name',
        'sernder',
        'working_strat_time',
        'working_end_time',
        'start_rest_time',
        'end_rest_time',
    ];

    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return asset('assets/images/logo.jpg');
        }
        return asset('storage/' . $this->image);
    }

    public function getCurrentNameLangAttribute()
    {
        $locale = app()->getLocale();
        if ($locale === 'ar' || empty($this->website_name_en)) {
            return $this->website_name;
        }
        return $this->website_name_en;
    }

    public function getCurrentAddressLangAttribute()
    {
        $locale = app()->getLocale();
        if ($locale === 'ar' || empty($this->address)) {
            return $this->address;
        }
        return $this->address_en;
    }

    public function getCurrentSubscription_titleLangAttribute()
    {
        $locale = app()->getLocale();
        if ($locale === 'ar' || empty($this->subscription_title)) {
            return $this->subscription_title;
        }
        return $this->subscription_title_en;
    }
}
