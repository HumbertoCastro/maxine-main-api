<?php

namespace App\Http\Middleware;

use App\Util\JSONResponse;
use App\Util\JWT\CheckToken;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $header = $request->header('Authorization') ?? null;
        // dd($request);

        if (!$header) {
            return JSONResponse::JSONReturn(false, 401, "Não autorizado, Autenticação necessária.", null);
        }

        $token = explode(' ', $header)[1];

        $checked_token = CheckToken::checkAuth($token);

        if(!$checked_token) {
            return JSONResponse::JSONReturn(false, 401, "Invalid Token", null);
        }

        if ($checked_token === 200) {
            return $next($request);
        }

        if($checked_token === 401) {
            return JSONResponse::JSONReturn(false, 401, "Token expired", null);
        }
    }
}
