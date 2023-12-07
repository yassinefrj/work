<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTruncation;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;


class ModalTasksDuskTest extends DuskTestCase
{
    use DatabaseTruncation;

    public function setUp(): void
    {
        parent::setUp();
        $this->browse(function (Browser $browser) {
            $browser->loginAs(User::find(1));
        });
    }
    public function testTaskDetailsModal()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/tasks')
                ->pause(1000)
                ->click("#buttonTask")
                ->pause(1000)
                ->assertSee('Task Details')
                ->assertSee('Name')
                ->assertSee('Description')
                ->assertSee('Number of participants')
                ->assertSee('Begin time')
                ->assertSee('End time')
                ->assertSee('Adresse')
                ->assertSee('minimum person')
                ->assertSee('maximum person');

        });
    }

    public function testTaskDetailsListPerson()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/tasks')
                ->pause(1000)
                ->click("#buttonTask")
                ->pause(1000)
                ->assertSee('Name')
                ->assertSee('Mail');

        });
    }


}
