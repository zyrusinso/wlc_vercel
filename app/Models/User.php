<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    // protected $fillable = [
    //     'full_name',
    //     'first_name',
    //     'middle_name',
    //     'last_name',
    //     'address',
    //     'email',
    //     'password',
    //     'endorsers_id',
    //     'referred_by',
    //     'role',
    //     'cp_num',
    //     'level',
    //     'cbb',
    //     'available_cash_bal',
    //     'total_cash_bonus',
    // ];

    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
    ];

    public static function userRoleList(){
        $data = [];
        $roles = UserRole::all();
        $data = ['super_admin' => 'Super Admin'];

        foreach($roles as $item){
            $data += [$item->role => $item->name];
        }

        return $data;
    }
    
    public static function userRoleRedirect($userRole){
        $role = UserRole::where('role', $userRole)->first();

        return $role->redirect_url;
    }

    // Temporarily hardcoded
    public static function routeNameList(){
        return [
            'dashboard' => 'Dashboard',
            'team' => 'Team',
            'transactions' => 'Transactions',
            'user-permissions' => 'User Permissions',
            'rewards' => 'Rewards',
            'store' => 'Store',
            'roles' => 'Roles',
        ];
    }

    public static function networkList($userData){
        $allUsers = User::get();
        $rootUsers = $allUsers->where('endorsers_id', $userData);

        self::networkListFormat($rootUsers, $allUsers);

        return $rootUsers;
    }

    public static function networkListFormat($rootUsers, $allUsers){
        foreach($rootUsers as $rootUser){
            $rootUser->children = $allUsers->where('referred_by', $rootUser->endorsers_id);

            if($rootUser->children->isNotEmpty()){
                self::networkListFormat($rootUser->children, $allUsers);
            }
        }
    }

    public static function networkRootList($userId){
        $allUsers = User::get();
        $currentUsers = $allUsers->where('endorsers_id', $userId);

        self::networkListRootFormat($currentUsers, $allUsers);

        return $currentUsers;
    }

    public static function networkListRootFormat($currentUsers, $allUsers){
        foreach($currentUsers as $currentUser){
            $currentUser->root = $allUsers->where('endorsers_id', $currentUser->referred_by);

            if($currentUser->root->isNotEmpty()){
                self::networkListRootFormat($currentUser->root, $allUsers);
            }
        }
    }

    public static function cbbDisplayColorCode(){
        return [
            '4' => 'text-danger',
            '3' => 'text-danger',
            '2' => 'text-warning',
            '1' => 'text-primary',
        ];
    }

    public static function cbbDisplayNumber(){
        return [
            '4' => '0',
            '3' => '1',
            '2' => '2',
            '1' => '3'
        ];
    }
}
