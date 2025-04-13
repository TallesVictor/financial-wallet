<?php


namespace App\Exceptions;

use Throwable;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Debug\ExceptionHandler;
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
        $code = $e instanceof HttpExceptionInterface
            ? $e->getStatusCode()
            : ($e->getCode() ?: 500);
            
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

