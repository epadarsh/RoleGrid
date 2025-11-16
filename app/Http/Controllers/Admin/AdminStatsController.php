<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminStatsController extends Controller
{
    public function index()
    {
        // Calculate the date 30 days ago
        $thirtyDaysAgo = now()->subDays(30);

        // 1. Total Users
        $totalUsers = User::count();

        // 2. New Referrals (Users created with a referrer_id in the last 30 days)
        $newReferrals = User::whereNotNull('referrer_id')
            ->where('created_at', '>=', $thirtyDaysAgo)
            ->count();

        // 3. Internal Products
        $internalProducts = Product::count();

        // 4. Admins Active (Users with role 'admin')
        $adminsActive = User::where('role', 'admin')->count();

        return response()->json([
            'totalUsers' => number_format($totalUsers),
            'newReferrals' => number_format($newReferrals),
            'internalProducts' => number_format($internalProducts),
            'adminsActive' => number_format($adminsActive),
        ]);
    }
}
