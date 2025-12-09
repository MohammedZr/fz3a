<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RefreshUserOnline
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
public function handle($request, Closure $next)
{
    if (auth()->check()) {

        // تجديد حالة المستخدم (خصوصًا للمسؤول)
        Cache::put('user-online-' . auth()->id(), true, now()->addMinutes(2));
    }

    return $next($request);
}

}
