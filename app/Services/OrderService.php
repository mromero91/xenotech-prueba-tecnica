<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\User;
use App\Patterns\Decorator\BaseOrderCalculator;
use App\Patterns\Decorator\MondayDiscountDecorator;
use App\Patterns\Decorator\RandomDiscountDecorator;
use App\Patterns\State\OrderStateContext;
use App\Patterns\Strategy\NotificationContext;
use Illuminate\Support\Facades\DB;

class OrderService
{
    private OrderStateContext $stateContext;
    private NotificationContext $notificationContext;

    public function __construct()
    {
        $this->stateContext = new OrderStateContext;
        $this->notificationContext = new NotificationContext();
    }

    public function createOrder(array $data, User $user): Order
    {
        logger()->info('Creating order', ['data' => $data, 'user' => $user]);
        return DB::transaction(function () use ($data, $user) {
            $order = Order::create([
                'user_id' => $user->id,
                'company_id' => $user->company_id,
                'status' => OrderStatus::PENDING->value,
                'customer_name' => $data['customer_name'],
                'total_amount' => 0,
            ]);

            if (isset($data['items']) && is_array($data['items'])) {
                foreach ($data['items'] as $item) {
                    $order->items()->create([
                        'product_name' => $item['product_name'],
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                    ]);
                }
            }

            $totalAmount = $this->calculateOrderTotal($order);
            $order->update(['total_amount' => $totalAmount]);

            return $order->load('items');
        });
    }

    public function updateOrderStatus(Order $order, string $status, User $user): bool
    {
        if (! $this->stateContext->canTransition($order, $status)) {
            return false;
        }

        $success = DB::transaction(function () use ($order, $status, $user) {
            $order->updated_by = $user->id;
            $result = $this->stateContext->transition($order, $status);

            if ($result) {
                if (!$user->relationLoaded('company')) {
                    $user->load('company');
                }

                $this->notificationContext->setStrategyByUser($user);
                $this->notificationContext->sendNotification($order->toJson());
            }

            return $result;
        });

        return $success;
    }

    public function calculateOrderTotal(Order $order): float
    {
        $calculator = new BaseOrderCalculator;
        $calculator = new MondayDiscountDecorator($calculator);
        $calculator = new RandomDiscountDecorator($calculator);

        return $calculator->calculate($order);
    }

    public function getAvailableTransitions(Order $order): array
    {
        return $this->stateContext->getAvailableTransitions($order);
    }

    public function listOrders(int $perPage = 15)
    {
        return Order::with(['user', 'items'])
            ->where('company_id', Auth()->user()->company_id)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    public function getOrder(int $id): ?Order
    {
        logger()->info('getOrder', ['company_id' => Auth()->user()->company_id]);
        return Order::with(['user', 'items'])
            ->where('company_id', Auth()->user()->company_id)
            ->find($id);
    }
}
