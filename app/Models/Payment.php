<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'user_id',
        'user_name',
        'package_reference',
        'order_number',
        'status',
        'source',
        'payment_id',
        'cur',
        'amount',
        'description',
    ];
    use HasFactory;
}
