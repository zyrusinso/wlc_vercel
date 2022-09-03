<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\UserPermission;
use App\Models\UserRole;

class ActivateProject extends Controller
{
    public function activate(){
        if(User::all()->count() < 1){
            User::create([
                'referred_by' => 'Creator',
                'endorsers_id' => 'WLC22-170322',
                'role' => 'super_admin',
                'full_name' => 'Super Admin',
                'email' => 'superadmin@gmail.com',
                'activation_code' => 'WLC-Creator',
                'email_verified_at' => now()->toDateTimeString(),
                'password' => Hash::make('wlc_superadmin#1234'),
                'level' => 0,
            ]);
            UserRole::create(
                [
                    'role' => 'product-endorsers', 
                    'name'=> 'Reseller', 
                    'redirect_url' => 'dashboard',
                    'redirect_url_name' => 'Dashboard',
                ],
                [
                    'role' => 'business-endorsers', 
                    'name'=> 'Business Endorser', 
                    'redirect_url' => 'dashboard',
                    'redirect_url_name' => 'Dashboard',
                ],
                [
                    'role' => 'user', 
                    'name'=> 'User', 
                    'redirect_url' => 'transactions',
                    'redirect_url_name' => 'Transactions',
                ],
                [
                    'role' => 'stockist', 
                    'name'=> 'Stockist', 
                    'redirect_url' => 'dashboard',
                    'redirect_url_name' => 'Dashboard',
                ],
                [
                    'role' => 'business-center', 
                    'name'=> 'Business Center', 
                    'redirect_url' => 'dashboard',
                    'redirect_url_name' => 'Dashboard',
                ],
            );

            UserPermission::create(
                // Normal User/ new Account
                [
                    'role' => 'User',
                    'route_url' => 'transactions',
                    'route_name' => 'Transactions',
                ],
                [
                    'role' => 'User',
                    'route_url' => 'store',
                    'route_name' => 'Store',
                ],

                // Reseller
                [
                    'role' => 'product-endorsers',
                    'route_url' => 'dashboard',
                    'route_name' => 'Dashboard',
                ],
                [
                    'role' => 'product-endorsers',
                    'route_url' => 'transactions',
                    'route_name' => 'Transactions',
                ],
                [
                    'role' => 'product-endorsers',
                    'route_url' => 'rewards',
                    'route_name' => 'Rewards',
                ],
                [
                    'role' => 'product-endorsers',
                    'route_url' => 'team',
                    'route_name' => 'Team',
                ],

                // Business Endorsers
                [
                    'role' => 'business-endorsers',
                    'route_url' => 'dashboard',
                    'route_name' => 'Dashboard',
                ],
                [
                    'role' => 'business-endorsers',
                    'route_url' => 'transactions',
                    'route_name' => 'Transactions',
                ],
                [
                    'role' => 'business-endorsers',
                    'route_url' => 'rewards',
                    'route_name' => 'Rewards',
                ],
                [
                    'role' => 'business-endorsers',
                    'route_url' => 'team',
                    'route_name' => 'Team',
                ],
            );

            return "Success";
        }else{
            return "Project is already Activated";
        }
    }
}
