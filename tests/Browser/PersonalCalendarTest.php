<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTruncation;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;

class PersonalCalendarTest extends DuskTestCase
{

    use DatabaseTruncation;
    /**
     * A Dusk test example.
     */
    public function test_non_admin_can_only_see_if_subbed_to_task(): void
    {

        $user = User::factory()->create();

        $this->browse(function (Browser $browser) use ($user) {
            $startDatetime = now()->addHour()->format("d-m-Y\tH:i");

            $endDatetime = now()->addHours(2)->format("d-m-Y\tH:i");

            $browser->loginAs($user)->visit('/create')
                ->type('name', 'Nom de tâche')
                ->type('description', 'Description de tache 3')
                ->type('people_count', 5)
                ->type('start_datetime', $startDatetime)
                ->type('end_datetime', $endDatetime)
                ->type('people_min', 3)
                ->type('people_max', 6)
                ->type('address', '123 Rue de Test, Ville Test')
                ->press('Submit')->assertSee('has successfully been added');

            $browser->visitRoute('tasks.index')
                ->waitForTextIn('tr:first-child td:first-child', "Tâche 1")
                // waiting for the AJAX script to finish
                ->click('tr:nth-of-type(7) .button-register');
                // nth-of-type(7) because 6 provided by factory, 7th is the one we just added

            $browser->visit('/calendar')->assertSee('Nom de tâche');
        });
    }

    public function test_regression_non_admin_can_see_task_they_registered(): void
    {
        $user = User::factory()->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser
                ->loginAs($user)
                ->visitRoute('tasks.index')
                ->waitForTextIn('tr:first-child td:first-child', "Tâche 1")
                ->click("tr:first-child .button-register")
                ->visit('/calendar')
                ->assertSee('Tâche 1');
        });
    }

}
