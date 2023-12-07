<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseTruncation;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Database\Factories\TaskFactory;
use App\Models\User;

class CalendarDuskTest extends DuskTestCase
{
    use DatabaseTruncation;

    public function setUp(): void 
    {
        parent::setUp();
        $this->browse(function (Browser $browser) {
            $browser->loginAs(User::find(1));
        });
    }
    /**
     * A Dusk test example.
     */
    public function testExample(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                    ->assertSee('Welcome to the Worktogether app');
        });
    }

    /**
     * Teste si l'administrateur peut voir toutes les tâches.
     *
     * @return void
     */
    public function testAdminCanSeeAllTasks()
    {
        // Utilisez la factory pour créer quelques tâches
        TaskFactory::new()->create([
            'name' => 'Tâche 7',
            // 'start_datetime' => Carbon::now(), // Utilisez la date et l'heure actuelles
            // 'end_datetime' => Carbon::now()->addDay(), // Ajouter un jour à la date actuelle
        ]);
        TaskFactory::new()->create(['name' => 'Tâche 2']);

        $this->browse(function (Browser $browser) {
            $browser->loginAs(User::find(1))
                ->visit(route('calendar'))
                ->assertSee('Calendar')
                ->assertSee('Tâche 7')
                ->assertSee('Tâche 1');
        });
    }
}
