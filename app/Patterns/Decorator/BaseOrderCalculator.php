<?php

namespace App\Patterns\Decorator;

use App\Models\Order;

class BaseOrderCalculator implements OrderCalculator
{
    public function calculate(Order $order): float
    {
        return $order->calculateTotal();
    }
}
