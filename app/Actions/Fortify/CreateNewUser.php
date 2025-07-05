<?php

namespace App\Actions\Fortify;

use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param array<string, string> $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'first_name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),

            ],

            'family_name' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'numeric'],
            'address' => ['required', 'string', 'max:255'],
            'country_id' => ['required', 'exists:countries,id'],
            'city_id' => ['required', 'exists:cities,id'],
            'country_code' => ['required', 'exists:countries,id'],

            'password' => $this->passwordRules(),
        ])->validate();


        $user = null;
        DB::transaction(function () use ($input, &$user) {
            $user = User::create([
                'first_name' => $input['first_name'],
                'email' => $input['email'],
                'phone_number' => $input['phone_number'],
                'family_name' => $input['family_name'],
                'address' => $input['address'],
                'password' => Hash::make($input['password']),
            ]);


            UserAddress::create([
                'address_title' => 'العنوان الأساسي',
                'first_name' => $user->first_name,
                'family_name' => $user->family_name,
                'phone_number' => $user->phone_number,
                'user_id' => $user->id,
                'address' => \request()->input('address'),
                'country_id' => $input['country_id'],
                'city_id' => $input['city_id'],
                'main_address' => true
            ]);

        });
        return $user;

    }
}
