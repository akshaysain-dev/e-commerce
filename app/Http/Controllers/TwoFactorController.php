<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use PragmaRX\Google2FA\Google2FA;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class TwoFactorController extends Controller
{
    /**
     * 🔹 Show QR Setup Page
     */
    public function setup()
    {
        if (!session('admin_id')) {
            return redirect()->route('admin');
        }

        $user = User::find(session('admin_id'));

        if (!$user) {
            return redirect()->route('admin');
        }

        $google2fa = new Google2FA();

        // 🔐 Always generate fresh secret (IMPORTANT)
        $secret = $google2fa->generateSecretKey();

        // ✅ SESSION me store karo (THIS WAS MISSING)
        session(['2fa_secret' => $secret]);

        // (optional) DB me temporarily save
        $user->update([
            'google2fa_secret' => encrypt($secret),
            'google2fa_enabled' => 0
        ]);

        // QR generate
        $qrUrl = $google2fa->getQRCodeUrl(
            config('app.name'),
            $user->email,
            $secret
        );

        $QR_Image = QrCode::size(200)->generate($qrUrl);

        return view('admin.2fa_setup', compact('QR_Image', 'secret'));
    }

    /**
     * 🔹 Enable 2FA (after scanning QR + OTP)
     */
    public function enable(Request $request)
    {
        $request->validate([
            'otp' => 'required'
        ]);

        $user = User::find(session('admin_id'));

        if (!$user) {
            return redirect()->route('admin');
        }

        $google2fa = new Google2FA();

        // ✅ SESSION se secret lo
        $secret = session('2fa_secret');

        if (!$secret) {
            return redirect()->route('admin.2fa.setup')
                ->withErrors(['otp' => 'Please setup 2FA first']);
        }

        if ($google2fa->verifyKey($secret, $request->otp)) {

            // ✅ FINAL SAVE
            $user->update([
                'google2fa_secret' => encrypt($secret),
                'google2fa_enabled' => 1
            ]);

            // session clear
            session()->forget('2fa_secret');

            return redirect()->route('admin_dashboard')
                ->with('success', '2FA Enabled Successfully');
        }

        return back()->withErrors(['otp' => 'Invalid OTP']);
    }

    /**
     * 🔹 Show OTP Verify Page (after login)
     */
    public function verifyPage()
    {
        if (!session('admin_id')) {
            return redirect()->route('admin');
        }

        return view('admin.2fa_verify');
    }

    /**
     * 🔹 Verify OTP after login
     */
    public function verify(Request $request)
    {
        $request->validate([
            'otp' => 'required'
        ]);

        $user = User::find(session('admin_id'));

        if (!$user) {
            return redirect()->route('admin');
        }

        $google2fa = new Google2FA();
        $secret = decrypt($user->google2fa_secret);

        if ($google2fa->verifyKey($secret, $request->otp)) {

            session(['2fa_verified' => true]);

            return redirect()->route('admin_dashboard');
        }

        return back()->withErrors(['otp' => 'Invalid OTP']);
    }

    /**
     * 🔹 Disable 2FA
     */
    public function disable(Request $request)
    {
        $request->validate([
            'otp' => 'required'
        ]);

        $user = User::find(session('admin_id'));

        if (!$user) {
            return redirect()->route('admin');
        }

        if (!$user->google2fa_secret) {
            return back()->withErrors(['otp' => '2FA is not enabled']);
        }

        $google2fa = new Google2FA();

        try {
            $secret = decrypt($user->google2fa_secret);
        } catch (\Exception $e) {
            return back()->withErrors(['otp' => 'Invalid secret']);
        }

        // 🔐 Verify OTP before disabling
        if ($google2fa->verifyKey($secret, $request->otp)) {

            $user->google2fa_secret = null;
            $user->google2fa_enabled = 0;
            $user->save();

            session()->forget('2fa_verified');

            return redirect()->route('admin_dashboard')
                ->with('success', '2FA Disabled Successfully');
        }

        return back()->withErrors(['otp' => 'Invalid OTP']);
    }
}