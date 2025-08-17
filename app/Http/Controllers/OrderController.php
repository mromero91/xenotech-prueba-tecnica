<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    private OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function index(Request $request): JsonResponse
    {
        $perPage = $request->get('per_page', 15);
        $orders = $this->orderService->listOrders($perPage);

        return response()->json([
            'data' => $orders->items(),
            'pagination' => [
                'current_page' => $orders->currentPage(),
                'last_page' => $orders->lastPage(),
                'per_page' => $orders->perPage(),
                'total' => $orders->total(),
            ],
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $order = $this->orderService->getOrder($id);

        if (! $order) {
            return response()->json([
                'message' => 'Order not found',
            ], 404);
        }

        $availableTransitions = $this->orderService->getAvailableTransitions($order);

        return response()->json([
            'data' => $order,
            'available_transitions' => $availableTransitions,
        ]);
    }

    public function store(CreateOrderRequest $request): JsonResponse
    {
        logger()->info(' **************** store', ['request' => $request->all()]);
        try {
            $order = $this->orderService->createOrder($request->validated(), $request->user());
            return response()->json([
                'message' => 'Order created successfully',
                'data' => $order,
            ], 201);
        } catch (\Exception $e) {
            logger()->error('Error in store method', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'message' => 'Error creating order',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(UpdateOrderRequest $request, int $id): JsonResponse
    {
        $order = $this->orderService->getOrder($id);

        if (! $order) {
            return response()->json([
                'message' => 'Order not found',
            ], 404);
        }

        $validatedData = $request->validated();
        try {
            $validatedData['updated_by'] = $request->user()->id;
            if (isset($validatedData['status'])) {
                $success = $this->orderService->updateOrderStatus($order,
                    $validatedData['status'], $request->user());

                if (! $success) {
                    return response()->json([
                        'message' => 'Invalid status transition',
                    ], 400);
                }
            }

            if (count($validatedData) > 1 || !isset($validatedData['status'])) {
                $order->update($validatedData);
            }

            $updatedOrder = $this->orderService->getOrder($id);
            $availableTransitions = $this->orderService->getAvailableTransitions($updatedOrder);

            return response()->json([
                'message' => 'Order updated successfully',
                'data' => $updatedOrder,
                'available_transitions' => $availableTransitions,
            ]);
        } catch (\Exception $e) {
            logger()->error('Error updating order', [
                'order_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'message' => 'Error updating order',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
