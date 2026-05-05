<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;

class AdminAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        // ✅ Check login
        if (!$request->session()->has('admin_id')) {
            return redirect()->route('admin')->with('error', 'Please login first.');
        }

        $user = User::find(session('admin_id'));

        if (!$user) {
            return redirect()->route('admin')->with('error', 'User not found.');
        }

        // ✅ Allow 2FA routes (IMPORTANT)
        $currentRoute = $request->route()->getName();

        if (in_array($currentRoute, [
            'admin.2fa.verify',
            'admin.2fa.check',
            'admin.2fa.setup',
            'admin.2fa.enable'
        ])) {
            return $next($request);
        }

        // 🔐 2FA Check
        if ($user->google2fa_enabled) {

            if (
                !$request->session()->has('2fa_verified') ||
                $request->session()->get('2fa_verified') !== true
            ) {
                return redirect()->route('admin.2fa.verify');
            }
        }

        return $next($request);
    }
}