<?php

namespace App\Patterns\Decorator;

use App\Models\Order;

class RandomDiscountDecorator extends OrderCalculatorDecorator
{
    public function calculate(Order $order): float
    {
        $baseTotal = $this->calculator->calculate($order);

        $dayOfWeek = now()->dayOfWeek;
        if ($dayOfWeek >= 1 && $dayOfWeek <= 4) {
            $randomDiscount = rand(1, 3) / 100;
            $discount = $baseTotal * $randomDiscount;

            return $baseTotal - $discount;
        }

        return $baseTotal;
    }
}
