<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\Auth\MagicAuthentication;
use Illuminate\Http\Request;

class MagicController extends Controller
{
    public function showMagicForm()
    {
        return view('auth.magic-login');
    }

    public function sendToken(Request $request, MagicAuthentication $magicAuthentication)
    {
        $this->validateForm($request);
        $magicAuthentication->requestLink();
    }

    protected function validateForm(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users']
        ]);
    }
}
