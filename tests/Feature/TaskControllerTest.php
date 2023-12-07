<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->actingAs(User::find(1))->get('/tasks');

        $response->assertStatus(200);
    }

    // public function test_getTasks_content(): void
    // {
    //     $response = $this->actingAs(User::find(1))->get('/tasks');

    //     $response->assertSee('Tâche 1');

    // }

    public function testGetTasks()
    {
        Task::factory()->count(3)->create();


         // Appelez la route correspondante (c'est-à-dire, celle qui exécute la méthode getTasks)
         $response = $this->actingAs(User::find(1))->get('/tasks'); 
         // cette route appelle la methode getTasks

         // Assurez-vous que la réponse est un succès (code de réponse HTTP 200)
         $response->assertStatus(200);
 
         // Vérifiez que la vue "tasks.show_task" est retournée
         $response->assertViewIs('tasks.show_task');
 
         // Vérifiez que les données de tâche sont présentes dans la vue
         $response->assertViewHas('tasks');
    }

    /**
     * TEST FOR REGISTER TO A TASK
     */

    public function test_register_to_task()
    {
        $user = User::find(1);
        $this->actingAs($user);

        $response = $this->postJson('/tasks/register', ['id_task' => 1]);
        $response->assertStatus(200)
                ->assertJson([
                    'message' => 'Inscription enregistrée'
                ]);
    }

    public function test_register_to_task_invalid_id()
    {
        $user = User::find(1);
        $this->actingAs($user);

        $response = $this->postJson('/tasks/register', ['id_task' => 0]);
        $response->assertStatus(200)
                ->assertJson([
                    'message' => 'Erreur id incorrect'
                ]);
    }

    public function test_register_to_task_exception()
    {
        $user = User::find(1);
        $this->actingAs($user);

        $response = $this->postJson('/tasks/register', ['id_task' => "test"]);
        $response->assertStatus(200)
                ->assertJson([
                    'message' => 'Erreur dans l\'inscription à la tâche',
                ]);
    }

    /**
     * TEST FOR UNREGISTER TO A TASK
     */

    public function test_unregister_to_task()
    {
        $user = User::find(1);
        $this->actingAs($user);

        $response = $this->postJson('/tasks/unregister', ['id_task' => 1]);
        $response->assertStatus(200)
                ->assertJson([
                    'message' => 'Désinscription enregistrée'
                ]);
    }

    public function test_unregister_to_task_invalid_id()
    {
        $user = User::find(1);
        $this->actingAs($user);

        $response = $this->postJson('/tasks/unregister', ['id_task' => 0]);
        $response->assertStatus(200)
                ->assertJson([
                    'message' => 'Erreur id incorrect'
                ]);
    }

    public function test_unregister_to_task_exception()
    {
        $user = User::find(1);
        $this->actingAs($user);

        $response = $this->postJson('/tasks/unregister', ['id_task' => "test"]);
        $response->assertStatus(200)
                ->assertJson([
                    'message' => 'Erreur dans la désinscription à la tâche',
                ]);
    }
}
