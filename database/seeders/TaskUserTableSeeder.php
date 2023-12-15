<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TaskUserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Insérer des données par défaut
        DB::table('task_user')->insert([
            'id_user' => 1,
            'id_task' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('task_user')->insert([
            'id_user' => 2,
            'id_task' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('task_user')->insert([
            'id_user' => 2,
            'id_task' => 2,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('task_user')->insert([
            'id_user' => 1,
            'id_task' => 3,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('task_user')->insert([
            'id_user' => 2,
            'id_task' => 4,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('task_user')->insert([
            'id_user' => 1,
            'id_task' => 5,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('task_user')->insert([
            'id_user' => 1,
            'id_task' => 4,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
