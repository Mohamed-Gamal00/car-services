<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscountCode extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'code', 'price', 'status', 'discount_type', 'number_of_used', 'product_ids'];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'discount_code_product');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'discount_user')->withPivot('used_at');
    }

}
