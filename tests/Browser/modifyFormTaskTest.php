<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTruncation;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;


class modifyFormTaskTest extends DuskTestCase
{
    use DatabaseTruncation;

    public function setUp(): void
    {
        parent::setUp();
        $this->browse(function (Browser $browser) {
            $browser->loginAs(User::find(1));
        });
    }
    public function test_task(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/tasks')
                ->assertSee("Tasks list");
        });
    }

    public function test_task_form_labels(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/tasks/1')
                ->assertSee("Task's name")
                ->assertSee('Description')
                ->assertSee('Number of participants')
                ->assertSee('Begin time')
                ->assertSee('End time')
                ->assertSee('Address')
                ->assertSee('Submit');
        });
    }



    public function test_task03_input_content(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/tasks/3')
                ->assertInputValue('name', 'Tâche 3')
                ->assertInputValue('description', 'Description de la tâche 3')
                ->assertInputValue('people_count', '4')
                ->assertInputValue('start_datetime', '2023-10-25T20:00')
                ->assertInputValue('end_datetime', '2023-10-25T22:00')
                ->assertInputValue('address', '425 NW 27th Avenue, Miami, FL 33125');
        });

    }

    public function test_task02_input_content(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/tasks/2')
                ->assertInputValue('name', 'Tâche 2')
                ->assertInputValue('description', 'Description de la tâche 2')
                ->assertInputValue('people_count', '5')
                ->assertInputValue('start_datetime', '2023-10-20T14:00')
                ->assertInputValue('end_datetime', '2023-10-20T16:00')
                ->assertInputValue('address', '456 Elm Street, Los Angeles, CA 90001');
        });

    }

    public function test_task_redirection(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/tasks')
                ->waitForTextIn('tr:first-child td:first-child', "Tâche 1")
                ->press('Modify')
                ->assertPathIs('/tasks/1');
        });

    }


    public function test_task_modify(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/create')
                ->type('name', 'Tâche 100')
                ->type('description', 'Description de la tache 100')
                ->type('people_count', '10')
                ->type('people_min', '2')
                ->type('people_max', '10')
                ->type('start_datetime', '23')
                ->type('start_datetime', '11')
                ->type('start_datetime', '2024')
                ->keys('#start_datetime', ['{tab}'])
                ->type('start_datetime', '10:00')
                ->type('end_datetime', '25')
                ->type('end_datetime', '11')
                ->type('end_datetime', '2024')
                ->keys('#end_datetime', ['{tab}'])
                ->type('end_datetime', '10:00')
                ->type('address', 'rue royale 107')
                ->press('Submit')
                ->visit('/tasks')
                ->waitForTextIn('tr:first-child td:first-child', "Tâche 1")
                ->assertSee('Tâche 100');

        });



        // ->pause(1000)
        // ->visit('/tasks') // Naviguer vers /tasks
        // ->assertSee('Tâche 100'); // Vérifier la présence du texte "Tache 100"
        // // ->visit('/tasks/')
        // ->assertSee('Tâche 100');

    }






}
