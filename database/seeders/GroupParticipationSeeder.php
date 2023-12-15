<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GroupParticipationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Assuming you have some users and groups already created
        $users = DB::table('users')->pluck('id');
        $groups = DB::table('groups')->pluck('id');

        // Seed group_participations with sample data
        foreach ($users as $userId) {
            foreach ($groups as $groupId) {
                DB::table('group_participations')->insert([
                    'user_id' => $userId,
                    'group_id' => $groupId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
