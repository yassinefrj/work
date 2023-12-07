<?php

namespace Database\Seeders;

use App\Models\Participation;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ParticipationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Participation::create([
            'id_task' => 1,
            'id_user' => 1,
        ]);
    }
}
