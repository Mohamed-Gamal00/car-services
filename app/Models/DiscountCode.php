<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscountCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'type',
        'value',
        'min_order_amount',
        'max_discount_amount',
        'usage_limit',
        'usage_limit_per_user',
        'used_count',
        'starts_at',
        'expires_at',
        'status',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'min_order_amount' => 'decimal:2',
        'max_discount_amount' => 'decimal:2',
        'starts_at' => 'date',
        'expires_at' => 'date',
    ];

    // Relationships
    public function services()
    {
        return $this->belongsToMany(Service::class, 'discount_code_services');
    }

    // Alias for backward compatibility
    public function products()
    {
        return $this->services();
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_discount_codes')
            ->withPivot('order_id', 'discount_amount', 'used_at')
            ->withTimestamps();
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active')
            ->where(function ($q) {
                $q->whereNull('starts_at')->orWhere('starts_at', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>=', now());
            });
    }

    public function scopeAvailable($query)
    {
        return $query->active()
            ->where(function ($q) {
                $q->whereNull('usage_limit')->orWhereRaw('used_count < usage_limit');
            });
    }

    // Helper methods
    public function isActive()
    {
        return $this->status === 'active' 
            && (is_null($this->starts_at) || $this->starts_at <= now())
            && (is_null($this->expires_at) || $this->expires_at >= now());
    }

    public function isAvailable()
    {
        return $this->isActive() 
            && (is_null($this->usage_limit) || $this->used_count < $this->usage_limit);
    }

    public function canBeUsedByUser($userId)
    {
        if (!$this->isAvailable()) {
            return false;
        }

        $userUsageCount = $this->users()->where('user_id', $userId)->count();
        return $userUsageCount < $this->usage_limit_per_user;
    }

    public function calculateDiscount($orderAmount)
    {
        if ($orderAmount < $this->min_order_amount) {
            return 0;
        }

        $discount = 0;
        
        if ($this->type === 'percentage') {
            $discount = ($this->value / 100) * $orderAmount;
        } else {
            $discount = $this->value;
        }

        if ($this->max_discount_amount && $discount > $this->max_discount_amount) {
            $discount = $this->max_discount_amount;
        }

        return min($discount, $orderAmount);
    }
}