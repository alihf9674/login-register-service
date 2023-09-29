<?php

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Http\Request;

class MagicAuthentication
{
    private $request;

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
}
