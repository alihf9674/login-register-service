<?php

namespace App\Services\Auth;

use App\Models\LoginToken;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MagicAuthentication
{
    private $request;

    const INVALID_TOKEN = 'token.invalid';
    const AUTHENTICATED = 'authenticated';

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function requestLink()
    {
        $user = User::where('email', $this->request->email)->firstOrFail();
        $user->createMagicToken()->send([
            'remember' => $this->request->has('remember')
        ]);
    }

    //if token wa valid, login user
    public function authenticate(LoginToken $token): string
    {
        $token->delete();

        if ($token->isExpired()) {
            return self::INVALID_TOKEN;
        }

        Auth::login($token->user, $this->request->query('remember'));

        return self::AUTHENTICATED;
    }
}
