<?php

namespace Database\Seeders;

use App\Models\Task;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Task::create([
            'name' => 'Tâche 1',
            'description' => 'Description de la tâche 1',
            'people_count' => 3,
            'start_datetime' => now(),
            'end_datetime' => now()->addDay(),
            'address' => '123 Main Street, New York, NY 10001',
        ]);

        Task::create([
            'name' => 'Tâche 2',
            'description' => 'Description de la tâche 2',
            'people_count' => 5,
            'start_datetime' => '2023-10-20 14:00:00',
            'end_datetime' => '2023-10-20 16:00:00',
            'address' => '456 Elm Street, Los Angeles, CA 90001',
        ]);

        Task::create([
            'name' => 'Tâche 3',
            'description' => 'Description de la tâche 3',
            'people_count' => 2,
            'start_datetime' => '2023-10-25 20:00:00',
            'end_datetime' => '2023-10-25 22:00:00',
            'address' => '425 NW 27th Avenue, Miami, FL 33125',
        ]);

        Task::create([
            'name' => 'Tâche 4',
            'description' => 'Description de la tâche 4',
            'people_count' => 2,
            'start_datetime' => '2023-10-25 20:00:00',
            'end_datetime' => '2023-10-25 22:00:00',
            'address' => 'Rue Royale 67, 1000 Bruxelles',
        ]);

        Task::create([
            'name' => 'Tâche 5',
            'description' => 'Description de la tâche 5',
            'people_count' => 4,
            'start_datetime' => '2023-10-25 20:00:00',
            'end_datetime' => '2023-10-25 22:00:00',
            'address' => 'Rue Royale 53, 1000 Bruxelles',
        ]);

        Task::create([
            'name' => 'Tâche 6',
            'description' => 'Description de la tâche 6',
            'people_count' => 4,
            'start_datetime' => '2023-10-25 20:00:00',
            'end_datetime' => '2023-10-25 22:00:00',
            'address' => 'Rue Royale 132, 1000 Bruxelles',
        ]);

    }
}
