<?php

namespace App\Patterns\Strategy;

use App\Enums\CustomerType;
use App\Models\User;
use App\Models\Company;
use Illuminate\Support\Collection;

class NotificationContext
{
    private Collection $strategies;
    private ?NotificationStrategy $currentStrategy = null;

    public function __construct()
    {
        $this->strategies = collect([
            new RegularCustomerStrategy(),
            new PremiumCustomerStrategy(),
            new VipCustomerStrategy(),
        ]);
    }

    public function setStrategyByCustomerType(string $customerType): self
    {
        $this->currentStrategy = $this->strategies->first(
            fn($strategy) => $strategy->getCustomerType() === $customerType
        );

        if (!$this->currentStrategy) {
            $this->currentStrategy = new RegularCustomerStrategy();
        }

        return $this;
    }


    public function setStrategyByUser(User $user): self
    {
        $customerType = $user->company->customer_type?->value ?? CustomerType::REGULAR->value;
        return $this->setStrategyByCustomerType($customerType);
    }

    public function setStrategyByCompany(Company $company): self
    {
        return $this->setStrategyByCustomerType($company->customer_type->value);
    }

    public function sendNotification(string $message, array $data = []): bool
    {
        logger()->info('sendNotification', ['message' => $message, 'data' => $data]);
        logger()->info('currentStrategy', ['currentStrategy' => $this->currentStrategy]);
        if (!$this->currentStrategy) {
            throw new \Exception('No strategy');
        }

        return $this->currentStrategy->sendNotification($message, $data);
    }

    public function getCurrentStrategy(): ?NotificationStrategy
    {
        return $this->currentStrategy;
    }

    public function getAvailableStrategies(): Collection
    {
        return $this->strategies;
    }

    public function getStrategyStats(): array
    {
        return $this->strategies->map(function ($strategy) {
            return [
                'customer_type' => $strategy->getCustomerType(),
                'priority' => $strategy->getPriority(),
                'class' => get_class($strategy),
            ];
        })->toArray();
    }
}
