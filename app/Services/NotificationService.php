<?php

namespace App\Services;

use App\Patterns\Strategy\NotificationContext;
use App\Models\User;
use App\Models\Company;
use App\Models\Order;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    private NotificationContext $context;

    public function __construct()
    {
        $this->context = new NotificationContext();
    }

    public function sendOrderStatusNotification(Order $order, string $newStatus): bool
    {
        $user = $order->user;
        $message = "Tu pedido #{$order->id} ha cambiado a estado: {$newStatus}";

        $data = [
            'order_id' => $order->id,
            'user_id' => $user->id,
            'email' => $user->email,
            'phone' => $user->phone ?? null,
            'old_status' => $order->getOriginal('status'),
            'new_status' => $newStatus,
            'total_amount' => $order->total_amount,
        ];

        try {
            $this->context->setStrategyByUser($user);
            return $this->context->sendNotification($message, $data);
        } catch (\Exception $e) {
            Log::error('Error sending order status notification', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    public function sendWelcomeNotification(User $user): bool
    {
        $message = "¡Bienvenido {$user->name}! Gracias por registrarte en nuestro sistema.";

        $data = [
            'user_id' => $user->id,
            'email' => $user->email,
            'phone' => $user->phone ?? null,
            'customer_type' => $user->customer_type?->value,
        ];

        try {
            $this->context->setStrategyByUser($user);
            return $this->context->sendNotification($message, $data);
        } catch (\Exception $e) {
            Log::error('Error sending welcome notification', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }



    public function getStrategyStats(): array
    {
        return $this->context->getStrategyStats();
    }
}
