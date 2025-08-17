<?php

namespace App\Patterns\Strategy;

interface NotificationStrategy
{
    public function sendNotification(string $message, array $data = []): bool;

    public function getCustomerType(): string;

    public function getPriority(): int;
}
