<?php

namespace Tests\Browser;

use App\Models\GroupParticipation;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTruncation;



class GroupParticipationDuskTest extends DuskTestCase
{
    use DatabaseTruncation;

    // setup authentication
    public function setUp(): void
    {
        parent::setUp();
        $this->browse(function (Browser $browser) {
            $browser->loginAs(User::find(1));
        });
    }

    /**
     * Tests registering to a group.
     */
    public function testRegisterToGroup()
    {
        $this->browse(function (Browser $browser) {

            $browser->visit('/groups')
                ->waitFor('#table-group table tbody tr');

            $registerButtons = $browser->elements('.btn-success');

            $lastRegisterButton = end($registerButtons);
            $lastRegisterButton->click();

            $browser->waitForText('Successfull')
                ->assertSee('Waiting');
        });
    }

    /**
     * Tests rejecting a participant.
     */
    public function testRejectParticipant()
    {
        GroupParticipation::create([
            'group_id' => 1,
            'user_id' => 1,
            'status' => 'waiting',
        ]);
        $this->browse(function (Browser $browser) {
            $browser->visit('/groups/participants')
                ->waitFor('.card')
                ->assertSee('User Name')
                ->assertSee('Status')
                ->assertSee('Actions')
                ->pause(2000)
                ->assertSee('admin')
                ->assertSee('waiting')
                ->assertSee('Accept')
                ->assertSee('Reject')
                ->press('Reject')
                ->waitFor('.swal2-container')
                ->within('.swal2-container', function ($dialog) {
                    $dialog->click('.swal2-confirm');
                });

            $browser->visit('/groups')
                ->pause(2000)
                ->assertDontSee('Unregister');
        });
    }

    /**
     * Tests accepting a participant.
     */
    public function testAcceptParticipant()
    {
        GroupParticipation::create([
            'group_id' => 1,
            'user_id' => 1,
            'status' => 'waiting',
        ]);
        $this->browse(function (Browser $browser) {
            $browser->visit('/groups/participants')
                ->waitFor('.card')
                ->assertSee('User Name')
                ->assertSee('Status')
                ->assertSee('Actions')
                ->pause(2000)
                ->assertSee('admin')
                ->assertSee('waiting')
                ->assertSee('Accept')
                ->assertSee('Reject')
                ->press('Accept')
                ->waitFor('.swal2-container')
                ->within('.swal2-container', function ($dialog) {
                    $dialog->click('.swal2-confirm');
                });
            $browser->visit('/groups')
                ->pause(2000)
                ->assertSee('Unregister');
        });
    }

    /**
     * Tests unregistering a participant.
     */
    public function testUnregisterParticipant()
    {
        GroupParticipation::create([
            'group_id' => 1,
            'user_id' => 1,
            'status' => 'waiting',
        ]);
        $this->browse(function (Browser $browser) {
            $browser->visit('/groups/participants')
                ->waitFor('.card')
                ->assertSee('User Name')
                ->assertSee('Status')
                ->assertSee('Actions')
                ->pause(2000)
                ->assertSee('admin')
                ->assertSee('waiting')
                ->assertSee('Accept')
                ->assertSee('Reject')
                ->press('Accept')
                ->waitFor('.swal2-container')
                ->within('.swal2-container', function ($dialog) {
                    $dialog->click('.swal2-confirm');
                });;
            $browser->visit('/groups')
                ->pause(2000)
                ->assertSee('Unregister')
                ->press('Unregister')
                ->waitFor('.swal2-container')
                ->within('.swal2-container', function ($dialog) {
                    $dialog->click('.swal2-confirm');
                })->pause(2000)->assertSee('Register');
        });
    }

    /**
     * Tests an empty participants list.
     */
    public function testEmptyParticipantsList()
    {

        $this->browse(function (Browser $browser) {
            $browser->visit('/groups/participants') // Assurez-vous de mettre l'URL correcte
                ->waitFor('.card')
                ->waitForText('No users waiting to be validated in this group')
                ->assertSee('No users waiting to be validated in this group');
        });
    }
}
