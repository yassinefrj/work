<?php

namespace Tests\Feature;

use Tests\TestCase;
use \App\Models\Participation;
use Illuminate\Support\Facades\DB;

use Database\Seeders\ParticipationSeeder;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ParticipationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic unit test example.
     */
    public function test_example(): void
    {
        $this->assertTrue(true);
    }

    public function testAddParticipationReturnsExpectedData()
    {
        Participation::add(99, 99);
        $this->assertDatabaseHas('participations',
            ['id_task' => 99, 'id_user' => 99]);
    }
}
