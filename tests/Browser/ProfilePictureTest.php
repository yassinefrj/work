<?php

namespace Tests\Browser;

use App\Models\User;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseTruncation;

class ProfilePictureTest extends DuskTestCase
{
    use DatabaseTruncation;

    public function setUp(): void
    {
        parent::setUp();
        $this->browse(function (Browser $browser) {
            $browser->loginAs(User::find(1));
        });
    }

    public function test_can_update_profile_picture(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/profile')
                    ->attach('picture', __DIR__ . '/img/avatars/april29th.png')
                    ->clickAndWaitForReload("form:nth-of-type(2) button[type='submit']")
                    ->assertAttributeContains("img[alt='Current Avatar']", "src", "avatars/");
        });
    }

    public function test_profile_picture_is_too_big(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/profile')
                    ->attach('picture', __DIR__ . '/img/avatars/too-big.png')
                    ->clickAndWaitForReload("form:nth-of-type(2) button[type='submit']")
                    ->assertSee("must not be greater");
        });
    }
}
