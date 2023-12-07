<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => "admin",
            'email' => "admin@he2b.be",
            'password' => "user",
            'isAdmin' => true,
            'is_verified' => true,
        ]);

        User::create([
            'name' => "user",
            'email' => "user@he2b.be",
            'password' => "user",
            'isAdmin' => false,
        ]);
    }

}
