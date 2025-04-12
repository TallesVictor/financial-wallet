<?php


namespace App\Exceptions;

use Throwable;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Debug\ExceptionHandler;

class CustomExceptionHandler implements ExceptionHandler
{
    public function report(Throwable $e): void
    {
        if (config('app.debug')) {
            dd($e);
        }

        // report($e);
    }

    public function render($request, Throwable $e): JsonResponse
    {
        return response()->json([
            // 'error' => $e->getMessage(),
            'message' => $e->getMessage(),
        ], $e->getCode() ?: 500);
    }

    public function renderForConsole($output, Throwable $e): void
    {
        $this->renderForConsole($output, $e);
    }

    public function shouldReport(Throwable $e): bool
    {
        return true;
    }
}

