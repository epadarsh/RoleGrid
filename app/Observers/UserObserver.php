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
    // /**
    //  * Handle the User "created" event.
    //  */
    // public function created(User $user): void
    // {
    //     //
    // }

    // /**
    //  * Handle the User "updated" event.
    //  */
    // public function updated(User $user): void
    // {
    //     //
    // }

    // /**
    //  * Handle the User "deleted" event.
    //  */
    // public function deleted(User $user): void
    // {
    //     //
    // }

    // /**
    //  * Handle the User "restored" event.
    //  */
    // public function restored(User $user): void
    // {
    //     //
    // }

    // /**
    //  * Handle the User "force deleted" event.
    //  */
    // public function forceDeleted(User $user): void
    // {
    //     //
    // }


}
