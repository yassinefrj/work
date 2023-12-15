<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Task;

class CalendarTest extends TestCase
{

    use RefreshDatabase; // toutes les modifications apportées à la base de données lors du test sont annulées après l'exécution du test,
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /** @test Ce test vérifie que, lorsqu'un utilisateur administrateur accède à la vue du calendrier,
     *  les données d'événements renvoyées par le contrôleur correspondent aux détails formatés de TOUTES les tâches créées 
     * dans la base de données, notamment le nom, les horaires, et l'URL de la carte associée à chaque tâche. */
    public function it_returns_calendar_view_for_admin_user_with_events()
    {
        // Créez un utilisateur administrateur
        $adminUser = User::factory()->create(['isAdmin' => true]);

        // Créez quelques tâches pour l'utilisateur
        $tasks = Task::all()->merge(Task::factory(3)->create());
        // NOTE: merges the newly created tasks with the ones already there (seeder)

        // // Agissez en tant qu'utilisateur administrateur
        $this->actingAs($adminUser);

        // // Appelez la méthode __invoke du contrôleur
        $response = $this->get(route('calendar'));

        // // Assurez-vous que la vue 'calendar.calendar' est renvoyée
        $response->assertViewIs('calendar.calendar');

        // // Assurez-vous que la variable 'events' est passée à la vue
        $response->assertViewHas('events');

        // Utilisez viewData pour obtenir les données de la vue
        $viewData = $response->viewData('events');

        //dd($tasks);

        // Assurez-vous que la variable 'events' contient les données attendues
        $expectedEvents = $tasks->map(function ($task) {
            return [
                'title' => $task->name,
                // NOTE: problem is that the tasks already there aren't Dates, they're strings
                // thus calling format throws an error. So I check the type before.
                'start' => gettype($task->start_datetime) == "string" ? $task->start_datetime : $task->start_datetime->format('Y-m-d H:i:s'), // Formatez la date
                'end' => gettype($task->end_datetime) == "string" ? $task->end_datetime : $task->end_datetime->format('Y-m-d H:i:s'), // Formatez la date
                'url' => route('maps', ['type' => 'gmaps', 'address' => $task->address]),
            ];
        })->toArray();

        $this->assertEquals($expectedEvents, $viewData);
    }

    /**
     * Test the download endpoint of the CalendarController.
     *
     * This test ensures that when a user with isAdmin property set to true
     * requests the calendar download endpoint, the response contains the
     * correct HTTP status code, Content-Type header, and Content-Disposition header.
     *
     * @return void
     */
    public function test_download(): void
    { {
            // Assuming you have a user with isAdmin property set to true
            $user = User::factory()->create(['isAdmin' => true]);

            $response = $this->actingAs($user)->get(route('calendar.download') . '?user=' . $user->id);


            $response->assertStatus(200)
                ->assertHeader('Content-Type', 'text/calendar; charset=UTF-8')
                ->assertHeader('Content-Disposition', 'attachment; filename="' . $user->name . '_event.ics"');
        }
    }
}
