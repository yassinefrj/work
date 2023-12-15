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
    /**
     * Dusk test to verify that clicking the copy button displays the success message.
     *
     * This test ensures that when a user clicks the copy button on the calendar page,
     * the expected success message is displayed.
     *
     * @return void
     */
    public function testCopyButtonShowsSuccessMessage()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(route('calendar')) // Update with the actual URL or route
                ->click('#copyIcsButton') // Update with the actual button ID or selector
                ->waitForText('The link has been successfully copied to your clipboard.')
                ->assertSee('The link has been successfully copied to your clipboard.');
        });
    }
}
