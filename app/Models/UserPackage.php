<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPackage extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'package_id',
        'reference',
        'remaining_washes',
        'start_date',
        'expiry_date',
        'status',
        'notified_expired',
    ];

    protected $casts = [
        'start_date' => 'date',
        'expiry_date' => 'date',
        'notified_expired' => 'boolean',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function payments()
    {
        return $this->morphMany(Payment::class, 'payable');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active')
            ->where('expiry_date', '>=', now())
            ->where('remaining_washes', '>', 0);
    }

    public function scopeExpired($query)
    {
        return $query->where('expiry_date', '<', now())
            ->where('status', '!=', 'expired');
    }

    public function scopeUsedUp($query)
    {
        return $query->where('remaining_washes', '<=', 0)
            ->where('status', '!=', 'used_up');
    }

    // Helper methods
    public function isActive()
    {
        return $this->status === 'active' 
            && $this->expiry_date >= now() 
            && $this->remaining_washes > 0;
    }

    public function isExpired()
    {
        return $this->expiry_date < now();
    }

    public function isUsedUp()
    {
        return $this->remaining_washes <= 0;
    }

    public function getUsagePercentage()
    {
        if (!$this->package) return 0;
        
        $totalWashes = $this->package->wash_count;
        $usedWashes = $totalWashes - $this->remaining_washes;
        
        return ($usedWashes / $totalWashes) * 100;
    }

    public function getDaysRemaining()
    {
        return now()->diffInDays($this->expiry_date, false);
    }
}
