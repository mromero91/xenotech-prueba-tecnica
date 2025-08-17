<?php

namespace App\Patterns\Strategy;

use App\Enums\CustomerType;
use App\Enums\PrioritiesType;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RegularCustomerStrategy implements NotificationStrategy
{
    public function sendNotification(string $message, array $data = []): bool
    {
        Log::info('[Notification] Regular Customer - No notification sent', [
            'message' => $message,
            'data' => $data,
            'method' => 'none'
        ]);

        // Cliente Regular: Sin notificación (solo log)
        return true;
    }

    public function getCustomerType(): string
    {
        return CustomerType::REGULAR->value;
    }

    public function getPriority(): int
    {
        return PrioritiesType::LOW->value;
    }
}
