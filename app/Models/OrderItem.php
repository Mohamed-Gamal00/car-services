<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class OrderItem extends Pivot
{
    use HasFactory;

    public $incrementing = true;
    public $timestamps = false;
    protected $table = 'order_items';

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

//    public function choices()
//    {
//        return $this->belongsToMany(Choice::class, 'choice_order_item', 'order_item_id', 'choice_id');
//    }

}
