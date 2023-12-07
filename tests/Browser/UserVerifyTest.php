<?php

namespace Tests\Browser;

use App\Models\User;

use Illuminate\Foundation\Testing\DatabaseTruncation;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class UserVerifyTest extends DuskTestCase
{
    use DatabaseTruncation;

    public function setUp(): void
    {
        parent::setUp();
        User::find(2)->delete();

        // this looks bad, but I need to delete the default user "user"
        // or else my Dusk "press" will be very complicated
    }
    public function test_unverified_user_can_see_silly_cat(): void
    {
        $this->browse(function (Browser $browser) {
            $user = User::factory()->unverified()->create();
            $user = User::find($user->id);
            
            $browser->loginAs($user);
            //dd($user);
            $browser->visit(route("tasks.index"))->assertSourceHas("img/cat.gif");
        });
        
    }

    public function test_verified_user_gets_where_they_want(): void
    {
        $this->browse(function (Browser $browser) {
            $user = User::factory()->create();
            $user = User::find($user->id);
            
            $browser->loginAs($user)
                ->visit(route("tasks.index"))->assertSee("Begin date");
        }); 
    }

    public function test_admin_can_see_user_verification_page(): void
    {
        $this->browse(function (Browser $browser) {
            $user = User::find(1);
            
            $browser->loginAs($user)
                ->visit("/")->assertSee("Admin");
        }); 
    }

    public function test_regular_user_cannot_see_user_verification_page(): void
    {
        $this->browse(function (Browser $browser) {
            $user = User::factory()->create();
            $user = User::find($user->id);
            
            $browser->loginAs($user)
                ->visit("/")->assertDontSee("Admin");
        }); 
    }

    public function test_admin_accepts_new_user(): void
    {
        $this->browse(function (Browser $browser) {
            $user = User::factory()->unverified()->create();
            $user = User::find($user->id);
            $admin = User::find(1);
            
            $browser->loginAs($user)
                ->visit(route("tasks.index"))->assertSourceHas("img/cat.gif");

            $browser->loginAs($admin)
                ->visit(route("verify"))
                ->assertSee($user->name)
                ->press("Accept")
                ->assertDontSee($user->name);

            $browser->loginAs($user)
                ->visit(route("tasks.index"))->assertSee("Begin date");
        }); 
    }

    public function test_admin_refuses_new_user(): void
    {
        $this->browse(function (Browser $browser) {
            $user = User::factory()->unverified()->create();
            $user = User::find($user->id);
            $admin = User::find(1);
            
            $browser->loginAs($admin)
                ->visit(route("verify"))
                ->assertSee($user->name)
                ->press("Refuse")
                ->assertDontSee($user->name);

            $this->assertDatabaseMissing("users", ["id" => $user->id]);
        }); 
    }
    
}
