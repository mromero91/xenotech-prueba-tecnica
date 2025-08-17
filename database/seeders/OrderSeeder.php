<?php

namespace Database\Seeders;

use App\Models\User;
use App\Services\OrderService;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $orderService = new OrderService;

        foreach ($users as $user) {
            $orderData = [
                'customer_name' =>$user->name,
                'items' => [
                    [
                        'product_name' => 'Rib eye',
                        'quantity' => 2,
                        'price' => 250.00,
                    ],
                    [
                        'product_name' => 'Flechita',
                        'quantity' => 1,
                        'price' => 50.00,
                    ],
                ],
            ];

            $order = $orderService->createOrder($orderData, $user);
        }
    }
}
