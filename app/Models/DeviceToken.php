<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeviceToken extends Model
{
    use HasFactory;

    protected $fillable = [
        'tokenable_type',
        'tokenable_id',
        'token',
        'device_type',
    ];

    // Relationships
    public function tokenable()
    {
        return $this->morphTo();
    }

    // Scopes
    public function scopeForUser($query, $userId)
    {
        return $query->where('tokenable_type', User::class)
            ->where('tokenable_id', $userId);
    }

    public function scopeForCaptain($query, $captainId)
    {
        return $query->where('tokenable_type', Captain::class)
            ->where('tokenable_id', $captainId);
    }
}