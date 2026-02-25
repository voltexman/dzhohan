<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class SetVisitorToken
{
    public function handle(Request $request, Closure $next): Response
    {
        // Якщо кукі вже є, просто йдемо далі
        if ($request->hasCookie('visitor_token')) {
            return $next($request);
        }

        // Генеруємо новий токен
        $token = Str::uuid()->toString();

        // Додаємо кукі до відповіді (тривалість 1 рік)
        $response = $next($request);

        return $response->withCookie(cookie()->forever('visitor_token', $token));
    }
}
