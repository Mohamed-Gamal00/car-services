<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'is_super_admin',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_super_admin' => 'boolean',
    ];

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
        return $query->where('status', 'active');
    }

    public function scopeSuperAdmin($query)
    {
        return $query->where('is_super_admin', true);
    }

    // Helper methods
    public function isSuperAdmin()
    {
        return $this->is_super_admin;
    }

    public function isActive()
    {
        return $this->status === 'active';
    }

    /**
     * Check if admin has a specific ability/permission
     * Super admins have all permissions
     * Regular admins need role system (not implemented yet)
     */
    public function hasAbility($ability)
    {
        // Super admins have all permissions
        if ($this->is_super_admin) {
            return true;
        }

        // For regular admins, you would check their role/group permissions here
        // Since role system is not implemented, grant all permissions for now
        // TODO: Implement proper role-based permissions
        return true;
    }
}