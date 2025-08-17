<?php
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

class Handler extends ExceptionHandler
{
    // ...

    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return response()->json([
            'ok'      => false,
            'code'    => 'UNAUTHENTICATED',
            'message' => 'No autenticado. Proporcione un token válido.',
        ], 401);
    }

    public function render($request, Throwable $e)
    {
        if ($e instanceof AuthorizationException) {
            return response()->json([
                'ok'      => false,
                'code'    => 'FORBIDDEN',
                'message' => $e->getMessage() ?: 'No tiene permisos para esta acción.',
            ], 403);
        }

        if ($e instanceof HttpExceptionInterface) {
            return response()->json([
                'ok'      => false,
                'code'    => strtoupper($e->getStatusCode() === 404 ? 'NOT_FOUND' : 'HTTP_ERROR'),
                'message' => $e->getMessage() ?: 'Error HTTP',
            ], $e->getStatusCode());
        }

        return parent::render($request, $e);
    }
}

