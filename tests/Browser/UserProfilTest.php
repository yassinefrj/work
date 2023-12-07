<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseTruncation;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;


class UserProfilTest extends DuskTestCase
{
    use DatabaseTruncation;
    /**
     * A Dusk test example.
     */
    public function testAdminButtonNotCreate(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->assertMissing('admin')
                ->assertSee('Login')
                ->click('.inline-flex')
                ->pause(2000)
                ->assertSee('Register')
                ->assertSee('Login')
                ->clickLink('Register') 
                ->assertPathIs('/register');
        });
    }

    public function testAdminButtonCreated(): void
    {
        parent::setUp();
        $this->browse(function (Browser $browser) {
            $browser->loginAs(User::find(1));
        });

        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->assertSee('admin');
        });
    }


    public function testUserDropdown()
    {
        parent::setUp();
        $this->browse(function (Browser $browser) {
            $browser->loginAs(User::find(1));
        });
        $this->browse(function (Browser $browser) {

            $browser->visit('/')
                ->assertSee('admin')
                ->click('.inline-flex')
                ->pause(2000)
                ->assertSee('Profile')
                ->assertSee('Log Out')
                ->clickLink('Profile') 
                ->assertPathIs('/profile'); 


        });
    }
}