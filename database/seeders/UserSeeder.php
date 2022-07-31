<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
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
        DB::table('users')->insert([
            'name' => 'root',
            'telefono' => '1234567890',
            'cedula' => '12345678901',
            'fecha_nacimiento' => '1997-02-07 00:00:00',
            'id_ciudad' => 1,
            'role' => 0,
            'email' => 'root@test.com',
            'password' => Hash::make('12345678'),
            'status' => 1,
        ]);
    }
}
