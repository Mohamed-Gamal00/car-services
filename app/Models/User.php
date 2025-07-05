<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use App\Models\UserAddress;
use App\Models\UserToken;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'email',
        'password',
        'family_name',
        'phone_number',
        'address',
        'image'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'email_verified_at',
        'two_factor_secret',
        'two_factor_recovery_codes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /* new */

    protected static function booted()
    {
        static::created(function ($user) {
            do {
                $token = encrypt(Str::random(60));
                $tokenExists = UserToken::where('token', $token)->exists();
            } while ($tokenExists);
            $user->userToken()->create([
                'token' => $token,
            ]);
        });
    }

    public function userToken()
    {
        return $this->hasOne(UserToken::class, 'user_id', 'id');
    }


    public function devicetokens()
    {
        return $this->hasMany(DeviceToken::class);
    }

    public function cars()
    {
        return $this->belongsToMany(Car::class, 'user_cars')
            ->withPivot('car_model', 'car_number')
            ->withTimestamps();
    }

    public function captain()
    {
        return $this->hasOne(Captain::class);
    }

    public function wishlistProducts()
    {
        return $this->belongsToMany(Product::class,
            'wishlist_products_user',
            'user_id',
            'product_id');
    }

    public function returnProducts()
    {
        return $this->belongsToMany(
            Product::class,
            'return_products',
            'user_id',
            'product_id'
        );
    }

    public function verificationCode()
    {
        return $this->hasOne(User_verfication::class, 'user_id', 'id');
    }

    public function addresses(): HasMany
    {
        return $this->hasMany(UserAddress::class, 'user_id', 'id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'user_id', 'id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'user_id', 'id');
    }

    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return asset('assets/images/no-image.jpg');
        }

        return asset('storage/' . $this->image);
    }

    public function scopeFilter(Builder $builder, $filters)
    {

        $builder->when($filters['phone_number'] ?? false, function ($builder, $value) {
            $cleanedValue = preg_replace('/^966/', '', $value);
            $builder->where('phone_number', 'LIKE', "%{$cleanedValue}%");
        });

    }

    public function discounts()
    {
        return $this->belongsToMany(DiscountCode::class, 'discount_user')->withPivot('used_at');
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class, 'user_id');
    }

    public function packages()
    {
        return $this->hasMany(UserPackage::class,'user_id');
    }

    public function activePackage()
    {
        return $this->packages()
            ->whereIn('status', ['active', 'used_up','expired'])
            ->latest()
            ->first();
    }

}
