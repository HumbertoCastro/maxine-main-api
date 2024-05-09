<?php

namespace App\Http\Middleware;

use Fruitcake\Cors\HandleCors;
use Illuminate\Support\Facades\Log;

class CustomCorsMiddleware extends HandleCors
{
    public function handle($request, \Closure $next)
    {
        Log::info('CORS middleware executed');
        return parent::handle($request, $next);
    }
}
