<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        "current_password",
        "password",
        "password_confirmation",
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->renderable(function (Throwable $e) {
            return response(json_encode([
                "ok" => false,
                "message" => $e->getMessage(),
            ]), $e->getCode() > 300 ? $e->getCode() : 500);
        });

        $this->renderable(function (\Exception $e) {
            return response(json_encode([
                "ok" => false,
                "message" => $e->getMessage(),
            ]), $e->getCode() > 300 ? $e->getCode() : 500);
        });
    }
}
