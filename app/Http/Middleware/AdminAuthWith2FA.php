<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;

class AdminAuthWith2FA
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        if (!session('admin_id')) {
            return redirect()->route('admin');
        }

        $user = User::find(session('admin_id'));

        if (!$user) {
            return redirect()->route('admin');
        }

        // 🔐 2FA check
        if ($user->google2fa_enabled) {

            if (!session()->has('2fa_verified') || session('2fa_verified') !== true) {
                return redirect()->route('admin.2fa.verify');
            }
        }

        return $next($request);
    }
}
