<?php

namespace App\Http\Controllers;

use App\Services\NotificationService;
use App\Models\User;
use App\Models\Company;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class NotificationController extends Controller
{
    private NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Enviar notificación de bienvenida a un usuario
     */
    public function sendWelcomeNotification(Request $request): JsonResponse
    {
        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        $user = User::findOrFail($request->user_id);
        
        $success = $this->notificationService->sendWelcomeNotification($user);
        
        return response()->json([
            'success' => $success,
            'message' => $success ? 'Notificación de bienvenida enviada' : 'Error al enviar notificación',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'customer_type' => $user->customer_type?->value
            ]
        ]);
    }

    /**
     * Enviar notificación de promoción a una empresa
     */
    public function sendPromotionNotification(Request $request): JsonResponse
    {
        $request->validate([
            'company_id' => 'required|exists:companies,id',
            'promotion_message' => 'required|string|max:500'
        ]);

        $company = Company::findOrFail($request->company_id);
        
        $success = $this->notificationService->sendPromotionNotification(
            $company, 
            $request->promotion_message
        );
        
        return response()->json([
            'success' => $success,
            'message' => $success ? 'Notificación de promoción enviada' : 'Error al enviar notificación',
            'company' => [
                'id' => $company->id,
                'name' => $company->name,
                'email' => $company->email,
                'customer_type' => $company->customer_type->value
            ]
        ]);
    }

    /**
     * Enviar notificación personalizada
     */
    public function sendCustomNotification(Request $request): JsonResponse
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'message' => 'required|string|max:1000',
            'additional_data' => 'sometimes|array'
        ]);

        $user = User::findOrFail($request->user_id);
        
        $success = $this->notificationService->sendCustomNotification(
            $user,
            $request->message,
            $request->get('additional_data', [])
        );
        
        return response()->json([
            'success' => $success,
            'message' => $success ? 'Notificación personalizada enviada' : 'Error al enviar notificación',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'customer_type' => $user->customer_type?->value
            ]
        ]);
    }

    /**
     * Obtener estadísticas de las estrategias
     */
    public function getStrategyStats(): JsonResponse
    {
        return response()->json([
            'strategies' => $this->notificationService->getStrategyStats(),
            'description' => 'Estadísticas de las estrategias de notificación disponibles'
        ]);
    }

    /**
     * Simular notificación de cambio de estado de pedido
     */
    public function simulateOrderStatusNotification(Request $request): JsonResponse
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'new_status' => ['required', Rule::in(['pending', 'processing', 'shipped', 'delivered', 'cancelled'])]
        ]);

        $order = Order::with('user')->findOrFail($request->order_id);
        
        $success = $this->notificationService->sendOrderStatusNotification(
            $order,
            $request->new_status
        );
        
        return response()->json([
            'success' => $success,
            'message' => $success ? 'Notificación de estado de pedido enviada' : 'Error al enviar notificación',
            'order' => [
                'id' => $order->id,
                'status' => $request->new_status,
                'user' => [
                    'id' => $order->user->id,
                    'name' => $order->user->name,
                    'customer_type' => $order->user->customer_type?->value
                ]
            ]
        ]);
    }
}
