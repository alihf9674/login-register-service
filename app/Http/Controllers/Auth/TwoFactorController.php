<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\Auth\TwoFactorAuthentication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TwoFactorController extends Controller
{

    public function showToggleForm()
    {
        return view('auth.two-factor.toggle');
    }

    public function activate(TwoFactorAuthentication $twoFactorAuthentication)
    {
        $response = $twoFactorAuthentication->requestCode(Auth::user());
    }
}
