<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTruncation;

class unregisterDuskTest extends DuskTestCase
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

    public function test_three_types_of_button(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/tasks')
                ->waitForTextIn('tr:first-child td:first-child', "Tâche 1")
                ->assertSeeIn('#tasks-body', 'Register')
                ->assertSeeIn('#tasks-body', 'Unregister')
                ->assertSeeIn('#tasks-body', 'Maximum Reached');
        });
    }
}
