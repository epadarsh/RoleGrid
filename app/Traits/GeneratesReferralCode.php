<?php

namespace App\Traits;

use Illuminate\Support\Str;
use App\Models\User;

trait GeneratesReferralCode
{
    protected static function generateUniqueReferralCode()
    {
        do {
            $code = Str::upper(Str::random(8));
        } while (User::where('referral_code', $code)->exists());

        return $code;
    }
}
