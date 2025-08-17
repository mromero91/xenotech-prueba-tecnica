<?php

namespace App\Models;

use App\Enums\CustomerType;
use App\Enums\UserRoleType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'company_id',
        'customer_type',
        'created_by',
        'updated_by',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected $appends = ['created_by_name', 'updated_by_name'];

    public function getCreatedByNameAttribute()
    {
        return $this->createdBy?->name;
    }

    public function getUpdatedByNameAttribute()
    {
        return $this->updatedBy?->name;
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by')->without(['createdBy', 'updatedBy']);
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by')->without(['createdBy', 'updatedBy']);
    }

    public function isAdmin(): bool
    {
        return $this->role === UserRoleType::ADMIN->value;
    }

    public function isPremium(): bool
    {
        return $this->customer_type === CustomerType::PREMIUM->value;
    }

    public function isVip(): bool
    {
        return $this->customer_type === CustomerType::VIP->value;
    }
}
