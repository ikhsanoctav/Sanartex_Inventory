<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'avatar',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isSuperadmin(): bool
    {
        return in_array($this->role, ['superadmin', 'owner', 'admin']);
    }

    public function isManajemen(): bool
    {
        return in_array($this->role, ['manajemen', 'direksi', 'owner', 'general_manager', 'superadmin']);
    }

    public function isKepalaGudang(): bool
    {
        return in_array($this->role, ['kepala_gudang', 'inventory_manager', 'manajemen', 'superadmin']);
    }

    public function isAdminGudang(): bool
    {
        return in_array($this->role, ['admin_gudang', 'staff_gudang', 'operator', 'superadmin', 'kepala_gudang']);
    }

    public function isPurchasing(): bool
    {
        return in_array($this->role, ['purchasing', 'procurement', 'manajemen', 'superadmin']);
    }

    public function getRoleLabelAttribute(): string
    {
        return match ($this->role) {
            'superadmin' => 'Superadmin',
            'manajemen', 'direksi', 'owner', 'general_manager' => 'Direksi / Manajemen Eksekutif',
            'kepala_gudang' => 'Kepala Gudang',
            'admin_gudang' => 'Admin Gudang',
            'purchasing' => 'Purchasing / Procurement',
            default => ucfirst(str_replace('_', ' ', $this->role ?? 'User')),
        };
    }

    public function getRoleBadgeClassAttribute(): string
    {
        return match ($this->role) {
            'superadmin' => 'bg-purple-50 text-purple-700 border border-purple-200',
            'manajemen', 'direksi', 'owner', 'general_manager' => 'bg-indigo-50 text-indigo-700 border border-indigo-300 font-bold',
            'kepala_gudang' => 'bg-sky-50 text-sky-700 border border-sky-200',
            'admin_gudang' => 'bg-emerald-50 text-emerald-700 border border-emerald-200',
            'purchasing', 'procurement' => 'bg-amber-50 text-amber-800 border border-amber-200',
            default => 'bg-slate-100 text-slate-700 border border-slate-200',
        };
    }
}
