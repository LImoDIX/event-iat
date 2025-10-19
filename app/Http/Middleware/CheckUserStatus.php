<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserStatus
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Проверяем, авторизован ли пользователь
        if (Auth::check()) {
            // Проверяем, заблокирован ли пользователь
            if (Auth::user()->is_blocked) {
                // Выходим из аккаунта заблокированного пользователя
                Auth::logout();
                
                // Перенаправляем на страницу входа с сообщением
                return redirect()->route('login')->with('error', 'Ваш аккаунт заблокирован. Обратитесь к администратору.');
            }
        }

        return $next($request);
    }
}
