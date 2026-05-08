<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VendorMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::guard('vendor')->check()) {

            return redirect()->route('vendor.login');
        }

        $user = Auth::guard('vendor')->user();

        if ($user->role !== 'vendor') {

            abort(403);
        }

        if ($user->status !== 'approved') {

            Auth::guard('vendor')->logout();

            return redirect()
                ->route('vendor.login')
                ->with('error', 'Account not approved.');
        }

        return $next($request);
    }
}