<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTruncation;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;

class TaskFormTest extends DuskTestCase
{
    use DatabaseTruncation;
    /**
     * A Dusk test example.
     */
    // FIXME: dead code
    /*
    public function test_form_labels(): void
    {
        $this->browse(function (Browser $browser) {
                $browser->visit('/create')
                    ->assertSee("Task's name")
                    ->assertSee('Description')
                    ->assertSee('Duration')
                    ->assertSee('Number of participants')
                    ->assertSee('Begin time')
                    ->assertSee('End time')
                    ->assertSee('Address')
                    ->assertSee('Submit');
        });
    }
    */

    public function setUp(): void
{
    parent::setUp();
    $this->browse(function (Browser $browser) {
        $browser->loginAs(User::find(1));
    });
}

    public function test_task_form_labels(): void
    {
        $this->browse(function (Browser $browser) {
                $browser->visit('/tasks/1')
                    ->assertSee("Task's name")
                    ->assertSee('Description')
                    ->assertSee('Number of participants')
                    ->assertSee('Begin time')
                    ->assertSee('End time')
                    ->assertSee('Address')
                    ->assertSee('Submit');
        });
    }

    public function test_task_form_validation_errors_empty_field():void
    {
        $this->browse(function (Browser $browser){
            $browser->visit('create')
            
                ->press('Submit')
                ->assertSourceMissing('Veuillez renseigner ce champ');
        }); 
    }

    public function test_task_validation_errors_name_length_31_field():void
    {
        $this->browse(function (Browser $browser) {
            $startDatetime = now()->addDay()->startOfDay()->addHours(8)->format('Y-m-d\TH:i');
            $endDatetime = now()->addDay()->addHour()->format('Y-m-d\TH:i');
            $browser->visit('/create')
                ->type('name','Nom de tâche avec plus de 30 caractères pour le test de validation')
                ->type('description','Description de tache 3')
                ->type('people_count','5')
                ->type('people_min', '2')
                ->type('people_max', '9')
                ->type('start_datetime',$startDatetime)
                ->type('end_datetime',$endDatetime)
                ->type('address','123 Rue de Test, Ville Test')
                ->press('Submit')
                ->assertSee('The name field must not be greater than 30 characters.');
        });
    }
    public function test_task_form_validation_errors_empty_description_field():void
    {
        $this->browse(function (Browser $browser){
            $startDatetime = now()->addDay()->startOfDay()->addHours(8)->format('Y-m-d\TH:i');
            $endDatetime = now()->addDay()->addHour()->format('Y-m-d\TH:i');

            $browser->visit('create')
            ->type('name','Tache3')
            ->type('people_count','4')
            ->type('people_min', '2')
            ->type('people_max', '9')
            ->type('start_datetime',$startDatetime)
            ->type('end_datetime',$endDatetime)
            ->type('address','123 Rue de Test, Ville Test')
            ->press('Submit')
            ->assertSourceMissing('Veuillez renseigner ce champ');
        }); 
    }

    public function test_task_validation_errors_description_length_256_field():void
    {
        $this->browse(function (Browser $browser) {
            $startDatetime = now()->addDay()->startOfDay()->addHours(8)->format('Y-m-d\TH:i');
            $endDatetime = now()->addDay()->addHour()->format('Y-m-d\TH:i');
            $browser->visit('/create')
                ->type('name','Tache3')
                ->type('description',str_repeat('a',265))
                ->type('people_count','5')
                ->type('people_min', '2')
                ->type('people_max', '9')
                ->type('start_datetime',$startDatetime)
                ->type('end_datetime',$endDatetime)
                ->type('address','123 Rue de Test, Ville Test')
                ->press('Submit')
                ->assertSee('The description field must not be greater than 255 characters.');
        });
    }

    public function test_task_form_validation_errors_empty_people_count_field():void
    {
        $this->browse(function (Browser $browser) {
            $startDatetime = now()->addDay()->startOfDay()->addHours(8)->format('Y-m-d\TH:i');
            $endDatetime = now()->addDay()->addHour()->format('Y-m-d\TH:i');
            $browser->visit('/create')
                ->type('name','Tache3')
                ->type('description','Description de tache 3')
                ->type('people_min', '2')
                ->type('people_max', '9')
                ->type('start_datetime',$startDatetime)
                ->type('end_datetime',$endDatetime)
                ->type('address','123 Rue de Test, Ville Test')
                ->press('Submit')
                ->assertSourceMissing('Veuillez renseigner ce champ');
        });
    }

    public function test_task_validation_errors_people_count_with_letter_field():void
    {
        $this->browse(function (Browser $browser) {
            $startDatetime = now()->addDay()->startOfDay()->addHours(8)->format('Y-m-d\TH:i');
            $endDatetime = now()->addDay()->addHour()->format('Y-m-d\TH:i');
            $browser->visit('/create')
                ->type('name','Tache3')
                ->type('description','descirption tache')
                ->type('people_count','a')
                ->type('start_datetime',$startDatetime)
                ->type('end_datetime',$endDatetime)
                ->type('address','123 Rue de Test, Ville Test')
                ->press('Submit')
                ->assertSourceMissing('Veuillez saisir un nombre.');
        });
    }
    

    public function test_task_form_validation_errors_empty_start_datetime_field():void
    {
        $this->browse(function (Browser $browser) {
            $endDatetime = now()->addDay()->addHour()->format('Y-m-d\TH:i');
            $browser->visit('/create')
                ->type('name','Tache3')
                ->type('description','Description de tache 3')
                ->type('people_count','4')
                ->type('people_min', '2')
                ->type('people_max', '9')
                ->type('end_datetime',$endDatetime)
                ->type('address','123 Rue de Test, Ville Test')
                ->press('Submit')
                ->assertSourceMissing('Veuillez renseigner ce champ');
        });
    }
    public function test_task_form_validation_errors_empty_end_datetime_field():void
    {
        $this->browse(function (Browser $browser) {
            $startDatetime = now()->addDay()->startOfDay()->addHours(8)->format('Y-m-d\TH:i');
            $endDatetime = now()->addDay()->addHour()->format('Y-m-d\TH:i');
            $browser->visit('/create')
                ->type('name','Tache3')
                ->type('description','Description de tache 3')
                ->type('people_count','4')
                ->type('people_min', '2')
                ->type('people_max', '9')
                ->type('start_datetime',$startDatetime)
                ->type('address','123 Rue de Test, Ville Test')
                ->press('Submit')
                ->assertSourceMissing('Veuillez renseigner ce champ');
        });
    }
    public function test_task_form_validation_errors_empty_address_field():void
    {
        $this->browse(function (Browser $browser) {
            $startDatetime = now()->addDay()->startOfDay()->addHours(8)->format('Y-m-d\TH:i');
            $endDatetime = now()->addDay()->addHour()->format('Y-m-d\TH:i');
            $browser->visit('/create')
                ->type('name','Tache3')
                ->type('description','Description de tache 3')
                ->type('people_count','4')
                ->type('people_min', '2')
                ->type('people_max', '9')
                ->type('start_datetime',$startDatetime)
                ->type('end_datetime',$endDatetime)
                ->press('Submit')
                ->assertSourceMissing('Veuillez renseigner ce champ');
        });
    }

    /*public function test_task_form_validation_errors_address_length_more_than_100_field():void
    {
        $this->browse(function (Browser $browser) {
            $startDatetime = now()->addDay()->startOfDay()->addHours(8)->format('Y-m-d\TH:i');
            $endDatetime = now()->addDay()->addHour()->format('Y-m-d\TH:i');
            $browser->visit('/create')
                ->type('name','Tache3')
                ->type('description','Description de tache 3')
                ->type('people_count','4')
                ->type('people_min', '2')
                ->type('people_max', '9')
                ->type('start_datetime',$startDatetime)
                ->type('end_datetime',$endDatetime)
                ->type('address','123 Rue de Test, Ville Test')
                ->press('Submit')
                ->assertSee('The address field must not be greater than 100 characters.');
        });
    }*/

    public function test_task_form_validation_errors_empty_min_field(): void 
    {
        $this->browse(function (Browser $browser){
            $startDatetime = now()->addDay()->startOfDay()->addHours(8)->format('Y-m-d\TH:i');
            $endDatetime = now()->addDay()->addHour()->format('Y-m-d\TH:i');
            $browser->visit('/create')
                ->type('name','Tache3')
                ->type('description','Description de tache 3')
                ->type('people_count','4')
                ->type('people_max', '9')
                ->type('start_datetime',$startDatetime)
                ->type('end_datetime',$endDatetime)
                ->type('address','123 Rue de Test, Ville Test')
                ->press('Submit')
                ->assertSourceMissing('Veuillez renseigner ce champ');
        });
    }

    public function test_task_form_validation_errors_empty_max_field(): void 
    {
        $this->browse(function (Browser $browser){
            $startDatetime = now()->addDay()->startOfDay()->addHours(8)->format('Y-m-d\TH:i');
            $endDatetime = now()->addDay()->addHour()->format('Y-m-d\TH:i');
            $browser->visit('/create')
                ->type('name','Tache3')
                ->type('description','Description de tache 3')
                ->type('people_count','4')
                ->type('people_min', '2')
                ->type('start_datetime',$startDatetime)
                ->type('end_datetime',$endDatetime)
                ->type('address','123 Rue de Test, Ville Test')
                ->press('Submit')
                ->assertSourceMissing('Veuillez renseigner ce champ');
        });
    }

    public function test_task_form_validation_errors_min_field(): void 
    {
        $this->browse(function (Browser $browser){
            $startDatetime = now()->addDay()->startOfDay()->addHours(8)->format('Y-m-d\TH:i');
            $endDatetime = now()->addDay()->addHour()->format('Y-m-d\TH:i');
            $browser->visit('/create')
                ->type('name','Tache3')
                ->type('description','Description de tache 3')
                ->type('people_count','9')
                ->type('people_min', '1')
                ->type('people_max', '4')
                ->type('start_datetime',$startDatetime)
                ->type('end_datetime',$endDatetime)
                ->type('address','123 Rue de Test, Ville Test')
                ->press('Submit')
                ->assertSee('The people min field must be greater than or equal to 2.');
        });
    }

    
}
