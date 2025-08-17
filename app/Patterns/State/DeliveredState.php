<?php

namespace App\Patterns\State;

use App\Models\Order;

class DeliveredState implements OrderState
{
    public function canTransitionTo(string $status): bool
    {
        return false;
    }

    public function transition(Order $order, string $status): bool
    {
        return false;
    }

    public function getAvailableTransitions(): array
    {
        return [];
    }
}
