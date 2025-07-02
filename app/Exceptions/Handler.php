<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Exceptions\UnauthorizedException;

class Handler extends ExceptionHandler
{

    protected $code = 500;
    protected $messages = 'Internal Server Error';

    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage(),
            ], $this->getStatusCode($exception));
        }

        return parent::render($request, $exception);
    }

    protected function getStatusCode(Throwable $exception)
    {
        if ($exception instanceof \Illuminate\Validation\ValidationException) {
            return 422; // Unprocessable Entity
        } elseif ($exception instanceof \Illuminate\Auth\AuthenticationException) {
            return 401; // Unauthorized
        } elseif ($exception instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
            return 404; // Not Found
        } elseif ($exception instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
            return 404; // Model Not Found
        } elseif ($exception instanceof \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException) {
            return 405; // Method Not Allowed
        } elseif ($exception instanceof \Illuminate\Auth\Access\AuthorizationException) {
            return 403; // Forbidden
        } else {
            return 500; // Internal Server Error (default)
        }
    }
}
