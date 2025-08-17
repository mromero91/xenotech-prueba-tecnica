<?php

namespace App\Patterns\Strategy;

use App\Enums\CustomerType;
use App\Enums\PrioritiesType;
use Illuminate\Support\Facades\Http;

class VipCustomerStrategy implements NotificationStrategy
{
    public function sendNotification(string $message, array $data = []): bool
    {
        logger()->info('[Notification] VIP Customer - WhatsApp notification', [
            'message' => $message,
            'data' => $data,
            'method' => 'whatsapp'
        ]);

        $webhookUrl = config('services.webhook.url', '');

        $payload = [
            'message' => $message,
            'data' => $data,
            'customer_type' => 'vip',
            'timestamp' => now()->toISOString(),
            'notification_method' => 'whatsapp',
        ];

        try {
            $response = Http::timeout(10)->post($webhookUrl, $payload);

            logger()->info('Webhook notification sent (VIP)', [
                'status_code' => $response->status(),
                'response' => $response->body(),
            ]);

            return $response->successful();
        } catch (\Exception $e) {
            logger()->error('Webhook notification failed (VIP)', [
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    public function getCustomerType(): string
    {
        return CustomerType::VIP->value;
    }

    public function getPriority(): int
    {
        return PrioritiesType::HIGH->value;
    }
}
