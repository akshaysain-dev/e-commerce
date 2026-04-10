<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\Customer;

class ForgotPasswordController extends Controller
{
    // Show form
    public function showForgotForm()
    {
        return view('auth.forgot-password');
    }

    // Send email
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:customers,email'
        ]);

        $token = Str::random(64);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            [
                'email' => $request->email,
                'token' => $token,
                'created_at' => Carbon::now()
            ]
        );

        $link = url('/reset-password/'.$token.'?email='.$request->email);

        Mail::raw("Click here to reset password: ".$link, function($message) use ($request){
            $message->to($request->email)
                    ->subject('Reset Password');
        });

        return back()->with('success', 'Reset link sent to your email');
    }

    // Show reset form
    public function showResetForm($token, Request $request)
    {
        $email = $request->query('email');

        $record = DB::table('password_reset_tokens')
            ->where('email', $email)
            ->where('token', $token)
            ->first();

        if (!$record) {
            return redirect()->route('customer_login')
                ->with('error', 'Invalid or expired reset link');
        }

        if (Carbon::parse($record->created_at)->addMinutes(30)->isPast()) {
            return redirect()->route('customer_login')
                ->with('error', 'Reset link expired');
        }

        return view('frontend.reset-password', [
            'token' => $token,
            'email' => $email
        ]);
    }

    // Reset password
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|confirmed|min:6'
        ]);

        $record = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->first();

        if (!$record) {
            return back()->with('error', 'Invalid token');
        }

        // Expire after 30 min
        if (Carbon::parse($record->created_at)->addMinutes(30)->isPast()) {
            return back()->with('error', 'Token expired');
        }

        Customer::where('email', $request->email)->update([
            'password' => bcrypt($request->password)
        ]);

        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect()->route('customer_login')->with('success', 'Password reset successful');
    }
}
