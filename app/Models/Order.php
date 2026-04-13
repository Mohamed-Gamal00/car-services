<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'number',
        'user_id',
        'service_id',
        'user_package_id',
        'captain_id',
        'car_id',
        'order_status_id',
        'car_model',
        'car_number',
        'address',
        'latitude',
        'longitude',
        'city',
        'district',
        'booking_date',
        'booking_time',
        'notes',
        'service_price',
        'discount_amount',
        'total_price',
        'payment_method',
        'payment_status',
        'is_arrived',
        'is_completed',
        'rating_skipped',
        'invoice_url',
    ];

    protected $casts = [
        'booking_date' => 'date',
        'booking_time' => 'datetime',
        'service_price' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_price' => 'decimal:2',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'is_arrived' => 'boolean',
        'is_completed' => 'boolean',
        'rating_skipped' => 'boolean',
    ];

    protected static function booted()
    {
        static::creating(function (Order $order) {
            $order->number = self::generateOrderNumber();
        });
    }

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function userPackage()
    {
        return $this->belongsTo(UserPackage::class);
    }

    public function captain()
    {
        return $this->belongsTo(Captain::class);
    }

    public function car()
    {
        return $this->belongsTo(Car::class);
    }

    public function orderStatus()
    {
        return $this->belongsTo(OrderStatus::class);
    }

    public function images()
    {
        return $this->hasMany(OrderImage::class);
    }

    public function rating()
    {
        return $this->hasOne(Rating::class);
    }

    public function payments()
    {
        return $this->morphMany(Payment::class, 'payable');
    }

    // Scopes
    public function scopeToday($query)
    {
        return $query->where('booking_date', now()->toDateString());
    }

    public function scopePending($query)
    {
        return $query->whereNull('captain_id')->where('payment_status', 'paid');
    }

    public function scopeAssigned($query)
    {
        return $query->whereNotNull('captain_id');
    }

    public function scopeCompleted($query)
    {
        return $query->where('is_completed', true);
    }

    // Helper methods
    public static function generateOrderNumber()
    {
        $prefix = 'QC';
        $date = now()->format('Ymd');
        $lastOrder = self::whereDate('created_at', now())->latest()->first();
        
        if ($lastOrder) {
            $lastNumber = (int) substr($lastOrder->number, -4);
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }
        
        return $prefix . $date . $newNumber;
    }

    public function isFromPackage()
    {
        return !is_null($this->user_package_id);
    }

    public function isPaid()
    {
        return $this->payment_status === 'paid';
    }

    public function isAssigned()
    {
        return !is_null($this->captain_id);
    }

    public function canBeCancelled()
    {
        return !in_array($this->order_status_id, [6, 7]); // Not in progress or completed
    }

    public function getStatusColorAttribute()
    {
        return $this->orderStatus->color ?? '#6c757d';
    }

    public function getStatusNameAttribute()
    {
        $locale = app()->getLocale();
        if ($locale === 'en' && $this->orderStatus->name_en) {
            return $this->orderStatus->name_en;
        }
        return $this->orderStatus->name;
    }

    public function getServiceDuration()
    {
        if ($this->service) {
            return $this->service->getDurationInMinutes();
        }
        
        if ($this->userPackage && $this->userPackage->package) {
            return $this->userPackage->package->getDurationInMinutes();
        }
        
        return 30; // Default 30 minutes
    }
}