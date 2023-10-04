<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
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

    //confirm two-factor authenticated code
    public function confirmCode(Request $request): \Illuminate\Http\RedirectResponse
    {
        $this->validateForm($request);

        $response = $this->twoFactorAuthentication->activate();

        return $response === $this->twoFactorAuthentication::ACTIVATED
            ? redirect()->route('home')->with('twoFactorActivated', true)
            : back()->with('invalidCode', true);
    }

    protected function validateForm($request)
    {
        $request->vlidate([
                'code' => ['required', 'numeric', 'digits:4']
            ]
            , [
                'code.digits' => __('auth.invalidCode')
            ]);
    }
}
