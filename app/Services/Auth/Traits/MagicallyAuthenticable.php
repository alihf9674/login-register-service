<?php

namespace App\Services\Auth\Traits;

use App\Models\LoginToken;
use Illuminate\Support\Str;

trait MagicallyAuthenticable
{
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
