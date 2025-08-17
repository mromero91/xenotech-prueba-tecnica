<?php

namespace App\Patterns\Decorator;

use App\Models\Order;

abstract class OrderCalculatorDecorator implements OrderCalculator
{
    protected OrderCalculator $calculator;

    public function __construct(OrderCalculator $calculator)
    {
        $this->calculator = $calculator;
    }

    public function calculate(Order $order): float
    {
        return $this->calculator->calculate($order);
    }
}
