<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use App\Rules\Recaptcha;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
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
     * @return \Illuminate\Http\RedirectResponse
     * @param \App\Http\Requests\Auth\LoginRequest $request
     * @throws ValidationException
     */
    public function store(LoginRequest $request)
    {
        $this->validateForm($request);
        if ($request->authenticate()) {
            $request->session()->regenerate();
            return redirect()->intended(RouteServiceProvider::HOME);
        }
    }


    protected function validateForm(LoginRequest $request)
    {
        $request->validate($request->rules());
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
}
