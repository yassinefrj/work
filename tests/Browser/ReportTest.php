<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTruncation;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;

class ReportTest extends DuskTestCase
{
    use DatabaseTruncation;
    public function test_admin_can_see_report_page_in_nav(): void
    {
        $this->browse(function (Browser $browser) {
            $user = User::find(1);

            $browser->loginAs($user)->visit("/")->assertSourceHas("Report");
        });
    }

    public function test_regular_user_cannot_see_user_verification_page(): void
    {
        $this->browse(function (Browser $browser) {
            $user = User::factory()->create();
            $user = User::find($user->id);

            $browser->loginAs($user)->visit("/")->assertSourceMissing("Report");
        });
    }
}
