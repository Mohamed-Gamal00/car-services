<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'avatar',
        'status',
        'preferred_language',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'phone_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Relationships
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function addresses()
    {
        return $this->hasMany(UserAddress::class);
    }

    public function cars()
    {
        return $this->belongsToMany(Car::class, 'user_cars')
            ->withPivot('car_model', 'car_number', 'is_default')
            ->withTimestamps();
    }

    public function packages()
    {
        return $this->hasMany(UserPackage::class);
    }

    public function discountCodes()
    {
        return $this->belongsToMany(DiscountCode::class, 'user_discount_codes')
            ->withPivot('order_id', 'discount_amount', 'used_at')
            ->withTimestamps();
    }

    public function deviceTokens()
    {
        return $this->morphMany(DeviceToken::class, 'tokenable');
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    // Accessors
    public function getAvatarUrlAttribute()
    {
        if (!$this->avatar) {
            return asset('assets/images/default-avatar.png');
        }
        return asset('storage/' . $this->avatar);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function activePackage()
    {
        return $this->packages()
            ->where('status', 'active')
            ->where('expiry_date', '>=', now())
            ->where('remaining_washes', '>', 0)
            ->first();
    }

    public function scopeFilter($query, $filters)
    {
        if (isset($filters['name'])) {
            $query->where('name', 'like', '%' . $filters['name'] . '%');
        }

        if (isset($filters['email'])) {
            $query->where('email', 'like', '%' . $filters['email'] . '%');
        }

        if (isset($filters['phone'])) {
            $query->where('phone', 'like', '%' . $filters['phone'] . '%');
        }

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query;
    }
}
