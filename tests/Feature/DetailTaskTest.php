<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
use App\Models\Participation;
use Tests\TestCase;

class DetailTaskTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        
        $user = User::factory()->create([
            'name' => 'user1',
            'email' => 'user1@he2b.be'
        ]);
        Participation::add(1, $user->id);
        $response = $this->call('GET', '/api/tasks/personList/1');
        
        
        $tasks = json_decode($response->getContent(), true);

        $userFound = false;

        foreach ($tasks as $task) {
            if ($task['name'] === 'user1' && $task['email'] === 'user1@he2b.be') {
                $userFound = true;
                break;
            }
        }
    
        $this->assertTrue($userFound);
    }
}
