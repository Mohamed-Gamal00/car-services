<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\OrderAddress;
use Illuminate\Support\Facades\DB;


class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'user_id',
        'captain_id',
        'car_id',
        'car_model',
        'car_number',
        'image',
        'note',
        'booking_date',
        'booking_time',
        'latitude',
        'longitude',
        'location',
        'order_status_id',
        'updated_by_admin',
        'payment_status',
        'status',
        'totalBeforeDiscount',
        'total_price',
        'payment_method',
        'return_order',
        'is_arrived',
        'discount_applied',
        'rating_skipped',
        'invoice_url',
        'is_delete',
        'user_package_id',
    ];


    protected static function booted()
    {
        static::creating(function (Order $order) {
            $order->number = Order::getNextOrderNumber();
        });

        static::addGlobalScope('notDeleted', function (Builder $builder) {
            $builder->where('is_delete', 0);
        });

    }

    public static function getNextOrderNumber()
    {
        $year = date('Y'); // or Carbon::now()->year()

        return DB::transaction(function () use ($year) {
            // Lock the table to prevent concurrent access
            $maxNumber = DB::table('orders')
                ->whereYear('created_at', $year)
                ->lockForUpdate()
                ->max('number');

            if ($maxNumber) {
                return $maxNumber + 1;
            }
            return $year . '0001';
        });
    }


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id')
            ->withDefault([
                'first_name' => 'زائر'
            ]);
    }

    public function car()
    {
        return $this->belongsTo(Car::class, 'car_id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'order_items', 'order_id', 'product_id', 'id')
            ->using(OrderItem::class)
            ->as('order_items')
            ->withPivot([
                'product_name', 'price', 'quantity',
            ]);
    }

    public function choices()
    {
        return $this->belongsToMany(Choice::class, 'order_choices', 'order_id', 'choice_id');
    }

    public function images()
    {
        return $this->hasMany(OrderImage::class, 'order_id');
    }

    public function captain()
    {
        return $this->belongsTo(Captain::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function orderStatus()
    {
        return $this->belongsTo(OrderStatus::class, 'order_status_id', 'id');
    }

    public function addresses()
    {
        return $this->hasMany(OrderAddress::class, 'order_id', 'id');
    }

    public function scopeFilter(Builder $builder, $filters)
    {
        $builder->when($filters['order_number'] ?? false, function ($builder, $value) {
            $builder->where('orders.number', 'LIKE', "%{$value}%");
        });

        $builder->when($filters['order_status_id'] ?? false, function ($builder, $value) {
            $builder->where('orders.order_status_id', $value);
        });

        $startAt = $filters['start_at'] ?? null;
        $endAt = $filters['end_at'] ?? null;

        if ($startAt && $endAt) {
            $builder->whereBetween('orders.created_at', [$startAt, $endAt]);
        } elseif ($startAt) {
            $builder->whereDate('orders.created_at', '=', $startAt);
        } elseif ($endAt) {
            $builder->whereDate('orders.created_at', '=', $endAt);
        }
    }


    public function rating()
    {
        return $this->hasOne(Rating::class);
    }

    public function averageRating()
    {
        return $this->ratings()->avg('stars');
    }

    public function userPackage()
    {
        return $this->belongsTo(UserPackage::class, 'user_package_id');
    }
}
