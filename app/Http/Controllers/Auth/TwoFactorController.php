<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Code;
use App\Services\Auth\TwoFactorAuthentication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TwoFactorController extends Controller
{
    protected $twoFactorAuthentication;

    public function __construct(TwoFactorAuthentication $twoFactorAuthentication)
    {
        $this->twoFactorAuthentication = $twoFactorAuthentication;
    }

    public function showToggleForm()
    {
        return view('auth.two-factor.toggle');
    }

    public function showEnterCodeForm()
    {
        return view('auth.two-factor.enter-code');
    }

    //set two-factor code for user
    public function activate(): \Illuminate\Http\RedirectResponse
    {
        $response = $this->twoFactorAuthentication->requestCode(Auth::user());

        return $response === $this->twoFactorAuthentication::CODE_SENT
            ? redirect()->route('auth.two.factor.code.form')
            : back()->with('cantSendCode', true);
    }

    /**
     * confirm two-factor authenticated code
     * @return \Illuminate\Http\RedirectResponse
     */
    public function confirmCode(Code $request)
    {
        $this->validateForm($request);

        $response = $this->twoFactorAuthentication->activate();

        return $response === $this->twoFactorAuthentication::ACTIVATED
            ? redirect()->route('home')->with('twoFactorActivated', true)
            : back()->with('invalidCode', true);
    }

    public function deactivate(): \Illuminate\Http\RedirectResponse
    {
        $this->twoFactorAuthentication->deactivate(Auth::user());

        return back()->with('twoFactorDeactivated', true);
    }

    public function resent(): \Illuminate\Http\RedirectResponse
    {
        $this->twoFactorAuthentication->resent();

        return back()->with('codeResent', true);
    }
}
