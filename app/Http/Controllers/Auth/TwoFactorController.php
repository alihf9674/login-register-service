<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\Auth\TwoFactorAuthentication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TwoFactorController extends Controller
{

    public function showToggleForm()
    {
        return view('auth.two-factor.toggle');
    }

    public function showEnterCodeForm()
    {
        return view('auth.two-factor.enter-code');
    }

    public function activate(TwoFactorAuthentication $twoFactorAuthentication)
    {
        $response = $twoFactorAuthentication->requestCode(Auth::user());

        return $response === $twoFactorAuthentication::CODE_SENT
            ? redirect()->route('auth.two.factor.code.form')
            : back()->with('cantSendCode', true);
    }
}
