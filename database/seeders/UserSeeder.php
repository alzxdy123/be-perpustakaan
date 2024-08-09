<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $owner = Role::where('name', 'owner')->first();
        $user = Role::where('name', 'user')->first();

        User::create([
            'username' => 'owner',
            'email' => 'owner@gmail.com',
            'password' => Hash::make('Owner123#'),
            'role_id' => $owner->id
        ]);

        User::create([
            'username' => 'Aldy BD',
            'email' => 'aldy@gmail.com',
            'password' => Hash::make('Aldy123#'),
            'role_id' => $user->id
        ]);

        User::create([
            'username' => 'Syarif Nurhakim',
            'email' => 'syarif@gmail.com',
            'password' => Hash::make('Syarif123#'),
            'role_id' => $user->id
        ]);

        User::create([
            'username' => 'Azi PY',
            'email' => 'azi@gmail.com',
            'password' => Hash::make('Azi123#'),
            'role_id' => $user->id
        ]);
        
    }
}
