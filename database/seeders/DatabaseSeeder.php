<?php

namespace Database\Seeders;

use App\Enums\UserType;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        DB::table('users')->insert([
            'email' => 'admin@gmail.com',
            'username' => 'admin',
            'role' => UserType::Admin,
            'password' => Hash::make('test123'),
        ]);

        DB::table('user_information')->insert([
            'user_id' => DB::getPdo()->lastInsertId(),
            'first_name' => 'Admin',
            'last_name' => 'Admin',
            'gender' => 'Male',
            'birthdate' => '2002-12-02',
            'country' => 'Philippines',
            'address' => 'Bataan',
        ]);
    }
}
