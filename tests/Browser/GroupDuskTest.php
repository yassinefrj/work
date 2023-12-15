<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTruncation;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class GroupDuskTest extends DuskTestCase
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
     * A basic browser test example.
     */
    public function testBasicExample(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/groups')
                ->pause(2000)
                ->assertsee('Group list')
                ->assertSee('Name')
                ->assertSee('Description');
        });
    }

    function test_Unique_Group_Name()
    {
        $groupName = 'Test Group Name';

        $this->browse(function (Browser $browser) use ($groupName) {
            $browser->visit('/add_group')
                ->type('name', $groupName)
                ->type('description', 'Test Group Description')
                ->press('submit');

            $this->assertDatabaseHas('groups', [
                'name' => $groupName,
            ]);

            $browser->visit('/add_group')
                ->type('name', $groupName)
                ->type('description', 'Another Test Group Description')
                ->press('submit')
                ->assertSee('The name has already been taken');
        });
    }


    /**
     * Test for inserting a group
     */
    public function test_insert_group()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/add_group')
                ->type('name', 'Group 3')
                ->type('description', 'This is a test group description')
                ->press('submit');
            $browser->visit('/groups')
                ->pause(2000)
                ->assertSee('Group 3')
                ->assertSee('This is a test group description');
        });
    }

    /**
     * Test for checking visibility of elements for inserting a group
     */
    public function test_show_element_insert()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/add_group')
                ->assertVisible('input[name="name"]')
                ->assertVisible('textarea[name="description"]')
                ->assertVisible('button[type="submit"]');
        });
    }

    /**
     * Test for validation errors when submitting without filling in required fields.
     */
    public function test_validation_errors()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/add_group')
                ->press('submit')
                ->assertSourceMissing('Veuillez renseigner ce champ.');
        });
    }

    /**
     * Test for validation error when the 'description' field is left empty.
     */
    public function test_validation_error_when_description_is_empty()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/add_group')
                ->type('name', 'group 4')
                ->press('submit')
                ->assertSourceMissing('Veuillez renseigner ce champ.');
        });
    }
}
