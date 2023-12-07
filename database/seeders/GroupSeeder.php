<?php

namespace Database\Seeders;

use App\Models\Group;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Group::create([
            'name' => 'Group 1',
            'description' => 'Description de la group 1',
        ]);

        Group::create([
            'name' => 'Group 2',
            'description' => 'Description de la group 2',
        ]);
    }
}
