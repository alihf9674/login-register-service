<?php

namespace App\Services\Auth;

use App\Models\TwoFactor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TwoFactorAuthentication
{
    protected $request;
    protected $code;

    const INVALID_CODE = 'code.invalid';
    const CODE_SENT = 'code.sent';
    const ACTIVATED = 'code.activated';
    const AUTHENTICATED = 'code.authenticated';

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    //send two-factor code
    public function requestCode(User $user): string
    {
        $code = TwoFactor::generateCodeFor($user);

        $this->setSession($code);

        $code->send();

        return static::CODE_SENT;
    }

    //activated two-factor
    public function activate(): string
    {
        if (!$this->isValidCode())
            return static::INVALID_CODE;

        $this->getToken()->delete();
        $this->getUser()->activateTwoFactor();

        $this->forgetSession();
        return static::ACTIVATED;
    }

    public function login()
    {
        if (!$this->isValidCode())
            return static::INVALID_CODE;

        $this->getToken()->delete();

        Auth::login($this->getUser(), session('remember'));

        $this->forgetSession();

        return static::AUTHENTICATED;
    }

    public function deactivate(User $user)
    {
        return $user->deactivateTwoFactor();
    }

    public function resent()
    {
        return $this->requestCode($this->getUser());
    }

    protected function setSession(TwoFactor $code)
    {
        session([
            'code_id' => $code->id,
            'user_id' => $code->user_id,
            'remember' => $this->request->remember
        ]);
    }

    protected function forgetSession()
    {
        session(['user_id', 'code_id', 'remember']);
    }

    protected function isValidCode(): bool
    {
        return $this->getToken()->isExpired() && $this->getToken()->isEqualWith($this->request->code);
    }

    //find user by id and code
    protected function getToken()
    {
        return $this->code ?? $this->code = TwoFactor::findOrFail(session('code_id'));
    }

    protected function getUser()
    {
        return User::findOrFail(session('user_id'));
    }
}
