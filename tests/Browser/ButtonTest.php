<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;

class ButtonTest extends DuskTestCase
{
    use RefreshDatabase;
    
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

    // FIXME: what do these tests even do ?
    public function testClassLogIn(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/');
            $browser->assertSeeIn('a.btn-secondary', 'Login');
        });
    }

    public function testClassRegister(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/');
            $browser->assertSeeIn('a.btn-primary', 'Register');
        });
    }
}
