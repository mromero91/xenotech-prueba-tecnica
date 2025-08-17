<?php

namespace App\Patterns\State;

use App\Models\Order;

class PendingState implements OrderState
{
    public function canTransitionTo(string $status): bool
    {
        return in_array($status, ['processing', 'cancelled']);
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
        return ['processing', 'cancelled'];
    }
}
