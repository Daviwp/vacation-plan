<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A lista das exceções que não devem ser reportadas.
     *
     * @var array
     */
    protected $dontReport = [];

    /**
     * A lista dos inputs que nunca devem ser incluídos em exceções de validação.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Registra os callbacks de exceção para a aplicação.
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Renderiza uma exceção em uma resposta HTTP.
     */
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof MethodNotAllowedHttpException) {
            return response()->json([
                'error' => 'Method Not Allowed',
                'message' => 'The ' . $request->method() . ' method is not supported for this route. Supported methods: ' . implode(', ', $exception->getAllowedMethods()),
            ], 405);
        }

        return parent::render($request, $exception);
    }
}
