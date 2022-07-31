<?php

namespace App\Actions\Fortify;

use App\Models\User;
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
     * @param  array  $input
     * @return \App\Models\User
     */
    public function create(array $input)
    {
        //creamos una regla personalizada para evaluar la edad y determinar si es mayor de edad o no
        Validator::extend('olderThan', function($attribute, $value, $parameters)
        {
            $minAge = ( ! empty($parameters)) ? (int) $parameters[0] : 18;
            return (new \DateTime)->diff(new \DateTime($value))->y >= $minAge;

            // or the same using Carbon:
            // return Carbon\Carbon::now()->diff(new Carbon\Carbon($value))->y >= $minAge;
        });
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'telefono' => ['string', 'max:10', 'min:10'],
            'cedula' => ['required', 'string', 'max:11', 'min:11'],
            'fecha_nacimiento' => ['required', 'date','olderThan'],
            'id_ciudad' => ['required'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],
            'password' => $this->passwordRules(),
        ],['older_than'=>'You must be 18 or older to register.'])->validate();

        return User::create([
            'name' => $input['name'],
            'telefono' => $input['telefono'],
            'cedula' => $input['cedula'],
            'fecha_nacimiento' => date('Y-m-d H:i:s', strtotime($input['fecha_nacimiento'])),
            'id_ciudad' => $input['id_ciudad'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
        ]);
    }
}