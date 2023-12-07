<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;

class unregisterDuskTest extends DuskTestCase
{

    use RefreshDatabase;

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
                ->waitFor('#tasks-body')
                ->assertSeeIn('#tasks-body', 'Register')
                ->assertSeeIn('#tasks-body', 'Unregister')
                ->assertSeeIn('#tasks-body', 'Maximum Reached');
        });
    }
}
