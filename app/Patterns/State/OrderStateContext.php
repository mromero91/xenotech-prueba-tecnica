<?php

namespace App\Patterns\State;

use App\Models\Order;

class OrderStateContext
{
    private array $states = [];

    public function __construct()
    {
        $this->states = [
            'pending' => new PendingState,
            'processing' => new ProcessingState,
            'shipped' => new ShippedState,
            'delivered' => new DeliveredState,
            'cancelled' => new CancelledState,
        ];
    }

    public function canTransition(Order $order, string $status): bool
    {
        $currentState = $this->states[$order->status->value] ?? null;

        if (! $currentState) {
            return false;
        }

        return $currentState->canTransitionTo($status);
    }

    public function transition(Order $order, string $status): bool
    {
        $currentState = $this->states[$order->status->value] ?? null;

        if (! $currentState) {
            return false;
        }

        return $currentState->transition($order, $status);
    }

    public function getAvailableTransitions(Order $order): array
    {
        logger()->info('getAvailableTransitions', ['order' => $order->status->value]);
        $currentState = $this->states[$order->status->value] ?? null;

        if (! $currentState) {
            return [];
        }

        return $currentState->getAvailableTransitions();
    }
}
