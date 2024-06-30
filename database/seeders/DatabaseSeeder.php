<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::create([
            'last_name' => 'el kolli',
            'first_name' => 'houssem',
            'email' => 'houssemElkolli@gmail.com',
            'password' => Hash::make('Test1234'),
            'address' => 'Algeria,Algiers',
            'phone_number' => '0123456789',
            'role' => 'admin'
        ]);
    }
}
