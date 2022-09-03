<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array  $input
     * @return \App\Models\User
     */
    public function create(array $input)
    {
        $validation = Validator::make($input, [
            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'cp_num' => ['required', 'numeric'],
            // 'activation_code' => ['required', 'numeric'],
            'endorsers_id' => ['required', 'string', 'exists:users,endorsers_id'],
            // 'password' => $this->passwordRules(),
            // 'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ], ['exists' => "The Endorsers ID is Invalid"]);

        // Check if user Firstname and Lastname Exist
        $validation->after(function($validation) use ($input) {
            if(count(User::where('first_name', $input['first_name'])->where('last_name', $input['last_name'])->get()) > 0){
                $validation->errors()->add('first_name', 'User already exists, please enter another user');
            }
        });
        $validation->validate();

        $UniqueEndorsersID = "WLC".now()->format('y')."-".mt_rand(100000, 999999);
        while(User::where('endorsers_id', $UniqueEndorsersID)->first()){
            $UniqueEndorsersID = "WLC".now()->format('y')."-".mt_rand(100000, 999999);
        }

        $registrationEndorsers = User::where('endorsers_id', $input['endorsers_id'])->first();
        $endorsersLevel = $registrationEndorsers->level;

        return User::create([
            'full_name' => $input['first_name'] . " " . $input['last_name'],
            'first_name' => $input['first_name'],
            'middle_name' => $input['middle_name'],
            'last_name' => $input['last_name'],
            'address' => $input['address'],
            'email' => $input['email'],
            'password' => Hash::make("wlc_pass#1234"),
            'cp_num' => $input['cp_num'],
            // 'activation_code' => $input['activation_code'],
            'endorsers_id' => $UniqueEndorsersID,
            'referred_by' => $input['endorsers_id'],
            'level' => $endorsersLevel++,
        ]);
    }
}
