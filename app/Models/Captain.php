<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Captain extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'phone',
        'password',
        'avatar',
        'status',
        'is_active',
        'latitude',
        'longitude',
        'notes',
        'preferred_language',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
        'is_active' => 'boolean',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    // Relationships
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function deviceTokens()
    {
        return $this->morphMany(DeviceToken::class, 'tokenable');
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
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
        return $query->where('is_active', true);
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', 'available')->where('is_active', true);
    }

    public function scopeBusy($query)
    {
        return $query->where('status', 'busy');
    }

    // Helper methods
    public function averageRating()
    {
        return $this->ratings()->avg('stars') ?? 0;
    }

    public function totalRatings()
    {
        return $this->ratings()->count();
    }

    public function isAvailable()
    {
        return $this->status === 'available' && $this->is_active;
    }

    public function isBusy()
    {
        return $this->status === 'busy';
    }

    public function getCurrentOrder()
    {
        return $this->orders()
            ->where('booking_date', now()->toDateString())
            ->whereIn('order_status_id', [3, 4, 5, 6]) // Assigned, On the way, Arrived, In progress
            ->first();
    }
}
