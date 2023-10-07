<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Code;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use App\Rules\Recaptcha;
use App\Services\Auth\TwoFactorAuthentication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    protected $twoFactorAuthentication;

    public function __construct(TwoFactorAuthentication $twoFactorAuthentication)
    {
        $this->twoFactorAuthentication = $twoFactorAuthentication;
    }

    public function showCodeForm()
    {
        return view('auth.two-factor.login-code');
    }

    /**
     * Display the login view.
     *
     * @return \Illuminate\View\View
     */

    public function create()
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     * @param \App\Http\Requests\Auth\LoginRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws ValidationException
     */
    public function store(LoginRequest $request)
    {
        $this->validateForm($request);
        $request->authenticate();
        $user = $this->getUser($request);

        if ($user->hasTwoFactor()) {
            $this->twoFactorAuthentication->requestCode($user);
            return $this->sendHasTwoFactorResponse();
        }
        Auth::login($user, $request->remember);

        $request->session()->regenerate();

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy()
    {
        session()->invalidate();
        Auth::logout();
        return redirect('/');
    }

    public function confirmCode(Code $code)
    {
        $response = $this->twoFactorAuthentication->login();

        return   $response === $this->twoFactorAuthentication::AUTHENTICATED
            ? $this->sendSuccessResponse()
            : back()->with('invalidCode', true);
    }

    protected function getUser($request)
    {
        return User::where('email', $request->email)->firstOrFail();
    }

    protected function sendHasTwoFactorResponse(): \Illuminate\Http\RedirectResponse
    {
        return redirect()->route('auth.login.code.form');
    }

    protected function validateForm(LoginRequest $request)
    {
        $request->validate($request->rules());
    }

    protected function sendSuccessResponse(): \Illuminate\Http\RedirectResponse
    {
        session()->regenerate();
        return redirect()->intended();
    }
}
