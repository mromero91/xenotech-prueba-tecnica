<?php

namespace App\Models;

use App\Enums\CustomerType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'customer_type',
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'customer_type' => CustomerType::class,
    ];

    /**
     * Relación con usuarios que pertenecen a esta empresa
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Relación con pedidos de esta empresa
     */
    public function orders()
    {
        return $this->hasManyThrough(Order::class, User::class);
    }

    /**
     * Usuario que creó la empresa
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by')->without(['createdBy', 'updatedBy']);
    }

    /**
     * Usuario que actualizó la empresa por última vez
     */
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by')->without(['createdBy', 'updatedBy']);
    }

    /**
     * Verificar si la empresa es de tipo regular
     */
    public function isRegular(): bool
    {
        return $this->customer_type === CustomerType::REGULAR;
    }

    /**
     * Verificar si la empresa es de tipo premium
     */
    public function isPremium(): bool
    {
        return $this->customer_type === CustomerType::PREMIUM;
    }

    /**
     * Verificar si la empresa es de tipo VIP
     */
    public function isVip(): bool
    {
        return $this->customer_type === CustomerType::VIP;
    }

    /**
     * Obtener empresas activas
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Obtener empresas por tipo de cliente
     */
    public function scopeByCustomerType($query, CustomerType $customerType)
    {
        return $query->where('customer_type', $customerType);
    }
}
