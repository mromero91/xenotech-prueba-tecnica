<?php

namespace App\Patterns\State;

use App\Models\Order;

interface OrderState
{
    public function canTransitionTo(string $status): bool;

    public function transition(Order $order, string $status): bool;

    public function getAvailableTransitions(): array;
}
