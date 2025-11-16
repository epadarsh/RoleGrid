<?php

namespace App\Observers;

use App\Models\User;
use App\Traits\GeneratesReferralCode;

class UserObserver
{
    use GeneratesReferralCode;

    public function creating(User $user)
    {
        // 1. Assign unique referral code before saving
        if (empty($user->referral_code)) {
            $user->referral_code = self::generateUniqueReferralCode();
        }

        // 2. Check for referrer and increment their count
        if ($user->referrer_id) {
            $referrer = User::find($user->referrer_id);
            if ($referrer) {
                // Increment cached referral count
                $referrer->increment('referral_count');
            }
        }
    }
}
