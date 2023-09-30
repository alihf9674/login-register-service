<?php

namespace App\Services\Auth\Traits;

use App\Models\LoginToken;
use Illuminate\Support\Str;

/*
 * use this trait in each model, you can use magic login (login via send email to valid email)
 */

trait MagicallyAuthenticable
{
    //relation from login token to each model inserted in

    public function magicToken(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(LoginToken::class);
    }

    public function createMagicToken(): \Illuminate\Database\Eloquent\Model
    {
        $this->magicToken()->delete();

        return $this->magicToken()->create([
            'token' => Str::random(50)
        ]);
    }
}
