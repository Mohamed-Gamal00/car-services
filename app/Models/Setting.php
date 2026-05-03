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
        'email',
        'phone_number',
        'whatsaap',
        'publishable_key',
        'secret_key',
        'sms_api_key',
        'sms_user_name',
        'sernder',
        'working_strat_time',
        'working_end_time',
        'start_rest_time',
        'end_rest_time',
        'logo',
        'image',
    ];
}
