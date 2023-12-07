<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTruncation;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;

class TaskTest extends DuskTestCase
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
     * A Dusk test example.
     */
    public function testExample(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs(User::find(1))->visit('/tasks') // Assurez-vous que cette URL correspond à la route de la page des tâches
                ->assertSee('Tasks list') // Vérifie que le titre de la page est présent
                ->assertVisible('.table') // Vérifie que la table des tâches est visible
                ->assertVisible('.table th.text-center'); // Vérifie que les en-têtes de colonne sont visibles
        });
    }

    public function test_min_max(): void 
    {
        $this->browse(function (Browser $browser){
            $browser->visit('/tasks')
                ->waitForTextIn('tr:first-child td:first-child', "Tâche 1")
                ->assertSee('Max participants')
                ->assertSee('3-5');
        });
    }

    public function test_visible_modify():void {
        $this->browse(function (Browser $browser){
            $browser
                ->loginAs(User::find(1))
                ->visit('/tasks')
                ->waitForTextIn('tr:first-child td:first-child', "Tâche 1")
                ->assertSee('Modify')
                ->assertSee('Modifcation');
        });
    }

    public function test_invisible_modify():void{
        $this->browse(function (Browser $browser){
            $browser->loginAs(User::factory()->create())
                ->visit('/tasks')
                ->waitForTextIn('tr:first-child td:first-child', "Tâche 1")
                ->assertSourceMissing('Modify')
                ->assertSourceMissing('Modifcation');
        });
    }

    public function test_regression_users_are_listed_in_details():void{
        $this->browse(function (Browser $browser) {
            $user = User::factory()->create();
            $browser->loginAs($user)
                ->visit('/tasks')
                ->waitForTextIn('tr:first-child td:first-child', "Tâche 1")
                ->click("tr:first-child .button-register")
                ->click('tr:first-child #buttonTask')
                ->waitForTextIn('#pearson_list', 'admin') //typo but what can you do
                ->assertSee($user->email);
        });
    }


/*    public function test_inscription_participants():void {
        $this->browse(function (Browser $browser) {
            $browser->visit('/tasks')
                ->click(".button-signUp","S'inscrire")
                ->assertSee("5","5")
                ->assertSeeIn('.button-registered', 'maximum reached');
        });
}*/
}

