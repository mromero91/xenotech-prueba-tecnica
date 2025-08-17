<?php

namespace App\Patterns\Decorator;

use App\Models\Order;

interface OrderCalculator
{
    public function calculate(Order $order): float;
}
