<?php

namespace App\Patterns\State;

use App\Models\Order;

class ProcessingState implements OrderState
{
    public function canTransitionTo(string $status): bool
    {
        return in_array($status, ['shipped', 'cancelled']);
    }

    public function transition(Order $order, string $status): bool
    {
        if (! $this->canTransitionTo($status)) {
            return false;
        }

        $order->status = $status;

        return $order->save();
    }

    public function getAvailableTransitions(): array
    {
        return ['shipped', 'cancelled'];
    }
}
