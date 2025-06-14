<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureUserIsDoctor
{
    public function handle(Request $request, Closure $next)
    {
        // تأكد أن المستخدم مسجل دخول
        if (!Auth::check()) {
            return redirect('/login')->with('error', 'يجب تسجيل الدخول أولاً.');
        }

        // تأكد أن role_id = 1 (دكتور)
        if (Auth::user()->role_id !== 1) {
            return redirect('/')->with('error', 'غير مصرح لك بالوصول إلى لوحة الطبيب.');
        }

        return $next($request);
    }
}

