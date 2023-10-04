<?php

namespace App\Services\Auth;

use App\Models\TwoFactor;
use App\Models\User;
use Illuminate\Http\Request;

class TwoFactorAuthentication
{
    protected $request;
    protected $code;

    const INVALID_CODE = 'code.invalid';
    const CODE_SENT = 'code.sent';
    const ACTIVATED = 'code.activated';

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
    public function activate()
    {
        if (!$this->isValidCode())
            return static::INVALID_CODE;

        $this->getToken()->delete();
        $this->getUser()->activateTwoFactor();

        $this->forgetSession();
        return static::ACTIVATED;
    }


    protected function setSession(TwoFactor $code)
    {
        session([
            'code_id' => $code->id,
            'user_id' => $code->user_id
        ]);
    }

    protected function forgetSession()
    {
        session(['user_id', 'code_id']);
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
