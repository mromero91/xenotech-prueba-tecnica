<?php

namespace App\Patterns\Strategy;

use App\Enums\CustomerType;
use App\Enums\PrioritiesType;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PremiumCustomerStrategy implements NotificationStrategy
{
    public function sendNotification(string $message, array $data = []): bool
    {


        // Cliente Premium: Notificación vía email al webhook
        $webhookUrl = config('services.webhook.url', 'https://webhook.site/263d24fd-e9c9-485f-a981-9a6d0f5c95ec');
        
        $payload = [
            'message' => $message,
            'data' => $data,
            'customer_type' => 'premium',
            'timestamp' => now()->toISOString(),
            'notification_method' => 'email',
        ];

        try {
            $response = Http::timeout(10)->post($webhookUrl, $payload);
            


            return $response->successful();
        } catch (\Exception $e) {


            return false;
        }
    }

    public function getCustomerType(): string
    {
        return CustomerType::PREMIUM->value;
    }

    public function getPriority(): int
    {
        return PrioritiesType::MEDIUM->value;
    }
}
