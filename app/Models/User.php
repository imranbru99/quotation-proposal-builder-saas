<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements FilamentUser
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $guarded = ['id'];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_platform_superadmin' => 'boolean',
            'is_active' => 'boolean',
            'custom_permissions' => 'array',
        ];
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function canAccessPanel(Panel $panel): bool
    {
        if ($panel->getId() === 'platform') {
            return (bool) $this->is_platform_superadmin;
        }

        if ($panel->getId() === 'app') {
            // Must belong to an active tenant or be platform superadmin
            return ($this->is_platform_superadmin || ($this->tenant_id !== null && $this->is_active));
        }

        return true;
    }

    public function isOwner(): bool
    {
        return $this->role === 'owner';
    }

    public function isAdmin(): bool
    {
        return in_array($this->role, ['owner', 'admin'], true);
    }

    public function isAccountant(): bool
    {
        return in_array($this->role, ['owner', 'admin', 'accountant'], true);
    }

    public function isSales(): bool
    {
        return in_array($this->role, ['owner', 'admin', 'sales_manager', 'sales_executive'], true);
    }

    public function hasRole(string ...$roles): bool
    {
        if ($this->is_platform_superadmin || $this->role === 'owner') {
            return true;
        }

        return in_array($this->role, $roles, true);
    }
}
