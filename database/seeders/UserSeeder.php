<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@rolegrid.com',
            'password' => Hash::make('Admin@12'),
            'role' => 'admin',
            'referral_code' => Str::upper(Str::random(8)),
        ]);

        $userA = User::create([
            'name' => 'Deric John',
            'email' => 'deric@rolegrid.com',
            'password' => Hash::make('Deric@12'),
            'role' => 'user',
            'referral_code' => Str::upper(Str::random(8)),
        ]);
        if ($userA->referrer_id) {
            $referrer = User::find($userA->referrer_id);
            if ($referrer) {
                $referrer->increment('referral_count');
            }
        }

        $userB = User::create([
            'name' => 'Embape',
            'email' => 'embape@rolegrid.com',
            'password' => Hash::make('Embape@12'),
            'role' => 'user',
            'referrer_id' => $userA->id,
            'referral_code' => Str::upper(Str::random(8)),
        ]);
        if ($userB->referrer_id) {
            $referrer = User::find($userB->referrer_id);
            if ($referrer) {
                $referrer->increment('referral_count');
            }
        }

        $userC = User::create([
            'name' => 'David Louis',
            'email' => 'david@rolegrid.com',
            'password' => Hash::make('David@12'),
            'role' => 'user',
            'referrer_id' => $userB->id,
            'referral_code' => Str::upper(Str::random(8)),
        ]);
        if ($userC->referrer_id) {
            $referrer = User::find($userC->referrer_id);
            if ($referrer) {
                $referrer->increment('referral_count');
            }
        }

        $this->command->info('Default Admin and Users created successfully.');
        $this->command->info('Admin: admin@rolegrid.com / password');
        $this->command->info('Users: deric@rolegrid.com, embape@rolegrid.com, david@rolegrid.com / password');
    }
}
