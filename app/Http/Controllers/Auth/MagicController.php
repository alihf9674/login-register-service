<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\LoginToken;
use App\Services\Auth\MagicAuthentication;
use Illuminate\Http\Request;

class MagicController extends Controller
{

    private $magicAuthentication;

    public function __construct(MagicAuthentication $magicAuthentication)
    {
        $this->magicAuthentication = $magicAuthentication;
    }

    public function showMagicForm()
    {
        return view('auth.magic-login');
    }

    public function sendToken(Request $request): \Illuminate\Http\RedirectResponse
    {
        $this->validateForm($request);
        $this->magicAuthentication->requestLink();
        return back()->with('magicLinkSent', true);
    }

    public function login(LoginToken $token): \Illuminate\Http\RedirectResponse
    {
        return $this->magicAuthentication->authenticate($token) === $this->magicAuthentication::AUTHENTICATED
            ? redirect()->route('home')
            : redirect()->route('auth.magic.login.form')->with('invalidToken', true);
    }

    protected function validateForm(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users']
        ]);
    }
}
