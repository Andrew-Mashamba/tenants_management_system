<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Validation\ValidationException;
use PragmaRX\Google2FA\Google2FA;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class TwoFactorAuthenticationController extends Controller
{
    use AuthorizesRequests;

    protected $google2fa;

    public function __construct(Google2FA $google2fa)
    {
        $this->google2fa = $google2fa;
        $this->middleware(['auth', 'password.confirm']);
    }

    public function show()
    {
        $user = Auth::user();
        $secret = $user->two_factor_secret;
        $qrCode = $this->google2fa->getQRCodeUrl(
            config('app.name'),
            $user->email,
            $secret
        );

        return view('auth.two-factor-authentication', [
            'qrCode' => $qrCode,
            'secret' => $secret,
            'recoveryCodes' => $user->two_factor_recovery_codes ? json_decode($user->two_factor_recovery_codes) : null,
        ]);
    }

    public function enable(Request $request)
    {
        $request->validate([
            'code' => ['required', 'string'],
        ]);

        $user = Auth::user();
        $valid = $this->google2fa->verifyKey($user->two_factor_secret, $request->code);

        if (!$valid) {
            throw ValidationException::withMessages([
                'code' => ['The provided two factor authentication code was invalid.'],
            ]);
        }

        //$user->enableTwoFactorAuth();
        $recoveryCodes ="";

        return view('auth.two-factor-recovery-codes', [
            'recoveryCodes' => $recoveryCodes,
        ]);
    }

    public function disable(Request $request)
    {
        $request->validate([
            'code' => ['required', 'string'],
        ]);

        $user = Auth::user();
        $valid = $this->google2fa->verifyKey($user->two_factor_secret, $request->code);

        if (!$valid) {
            throw ValidationException::withMessages([
                'code' => ['The provided two factor authentication code was invalid.'],
            ]);
        }

        //$user->disableTwoFactorAuth();

        return redirect()->route('profile.show')->with('status', 'Two-factor authentication has been disabled.');
    }

    public function verify(Request $request)
    {
        $request->validate([
            'code' => ['required', 'string'],
        ]);

        $user = Auth::user();
        $valid = $this->google2fa->verifyKey($user->two_factor_secret, $request->code);

        if (!$valid) {
            throw ValidationException::withMessages([
                'code' => ['The provided two factor authentication code was invalid.'],
            ]);
        }

        session(['2fa_verified' => true]);

        return redirect()->intended();
    }

    public function recovery(Request $request)
    {
        $request->validate([
            'recovery_code' => ['required', 'string'],
        ]);

        $user = Auth::user();
        $recoveryCodes = json_decode($user->two_factor_recovery_codes, true);

        if (!in_array($request->recovery_code, $recoveryCodes)) {
            throw ValidationException::withMessages([
                'recovery_code' => ['The provided recovery code was invalid.'],
            ]);
        }

        $recoveryCodes = array_diff($recoveryCodes, [$request->recovery_code]);
        $user->two_factor_recovery_codes = json_encode($recoveryCodes);
       // $user->save();

        session(['2fa_verified' => true]);

        return redirect()->intended();
    }
}
