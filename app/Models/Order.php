<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'company_id',
        'total_amount',
        'status',
        'customer_name',
        'updated_by',
    ];

    protected $casts = [
        'status' => OrderStatus::class,
        'total_amount' => 'decimal:2',
    ];

    // public function boot()
    // {
    //     parent::boot();
    //     static::creating(function ($order) {
    //         $order->status = OrderStatus::PENDING->value;
    //         $order->created_by = auth()->check() ? auth()->user()->id : $order->created_by;
    //         $order->updated_by = auth()->check() ? auth()->user()->id : $order->updated_by;
    //     });
    // }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by')->without(['createdBy', 'updatedBy']);
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by')->without(['createdBy', 'updatedBy']);
    }

    public function canTransitionTo(string $status): bool
    {
        $transitions = [
            OrderStatus::PENDING->value =>
                [OrderStatus::PROCESSING->value, OrderStatus::CANCELLED->value],
            OrderStatus::PROCESSING->value =>
                [OrderStatus::SHIPPED->value, OrderStatus::CANCELLED->value],
            OrderStatus::SHIPPED->value =>
                [OrderStatus::DELIVERED->value, OrderStatus::CANCELLED->value],
            OrderStatus::DELIVERED->value => [],
            OrderStatus::CANCELLED->value => [],
        ];

        return in_array($status, $transitions[$this->status] ?? []);
    }

    public function calculateTotal(): float
    {
        return $this->items->sum('subtotal');
    }
}
