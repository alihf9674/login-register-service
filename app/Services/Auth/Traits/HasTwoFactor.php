<?php

namespace App\Services\Auth\Traits;

use App\Models\TwoFactor;

trait HasTwoFactor
{
    public function code(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(TwoFactor::class);
    }

    public function activateTwoFactor()
    {
        $this->has_two_factor = true;
        $this->save();
    }

    public function hasTwoFactor(): bool
    {
        return $this->has_two_factor;
    }

    public function deactivateTwoFactor()
    {
        $this->has_two_factor = false;
        $this->save();
    }
}
