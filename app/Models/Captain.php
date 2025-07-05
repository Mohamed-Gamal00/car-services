<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Captain extends Authenticatable

{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'last_name',
        'phone_number',
        'status',
        'is_active',
        'password',
        'latitude',
        'longitude',
        'image',
        'notes',
        'remember_token'
    ];
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }


    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return asset('assets/images/no-image.jpg');
        }
        return asset('storage/' . $this->image);
    }

    public function devicetokens()
    {
        return $this->hasMany(DeviceToken::class);
    }

    public function averageRating()
    {
        return $this->ratings()->avg('stars');
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class, 'captain_id');
    }

}
