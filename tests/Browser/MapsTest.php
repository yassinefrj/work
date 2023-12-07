<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTruncation;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;

class MapsTest extends DuskTestCase
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

    public function testRedirectedToGMaps(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/tasks');
            $linkText = '123 Main Street, New York, NY 10001';
            if ($browser->seeLink($linkText)) {
                $browser->clickLink($linkText);
                $window = collect($browser->driver->getWindowHandles())->last();
                $browser->driver->switchTo()->window($window);
                $browser->press('Tout refuser');
                $url = $browser->driver->getCurrentURL();
                $this->assertEquals('https://www.google.com/maps/search/?api=1&query=123+Main+Street,+New+York,+NY+10001', $url);
            }
        });
    }

    public function testSeeCalendar(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/calendar')
            ->assertSee('Calendar')
            ->assertSee('Sun')
            ->assertSee('Mon')
            ->assertSee('Tue')
            ->assertSee('Wed')
            ->assertSee('Thu')
            ->assertSee('Fri')
            ->assertSee('Sat');
        });
    }
}
