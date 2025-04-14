<?php


namespace App\Exceptions;

use Throwable;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class CustomExceptionHandler implements ExceptionHandler
{
    public function report(Throwable $e): void
    {
        if (env('APP_DEBUG_DD')) {
            dd($e);
        }
    }

    public function render($request, Throwable $e): JsonResponse
    {
        $code = 500;

        if ($e instanceof HttpExceptionInterface) {
            if ($e->getStatusCode() > 0) $code = $e->getStatusCode();
        } elseif ($e instanceof ValidationException) {
            $code = 422;
        }

        return response()->json([
            'message' => $e->getMessage() ?: 'Something went wrong',
        ], $code);
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
