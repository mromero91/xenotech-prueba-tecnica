<?php

namespace App\Patterns\Decorator;

use App\Models\Order;

class MondayDiscountDecorator extends OrderCalculatorDecorator
{
    public function calculate(Order $order): float
    {
        $baseTotal = $this->calculator->calculate($order);

        if (now()->isMonday()) {
            $discount = $baseTotal * 0.10;

            return $baseTotal - $discount;
        }

        return $baseTotal;
    }
}
