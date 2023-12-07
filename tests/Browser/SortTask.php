<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseTruncation;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;

class SortTask extends DuskTestCase
{
    use DatabaseTruncation;


    public function setUp(): void
    {
        parent::setUp();
        $this->browse(function (Browser $browser) {
            $browser->loginAs(User::find(1));
        });
    }

    /**
     * Test sorting tasks by number of participants ascendant.
     *
     * @return void
     */
    public function testSortByParticipantsAsc()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/tasks')
                ->waitFor('#tasks-body');

            $browser->select('#sort', 'participants_asc')
                ->pause(100)

                ->assertSeeIn('#tasks-body', 'Tâche 1')
                ->assertSeeIn('#tasks-body', 'Tâche 2')
                ->assertSeeIn('#tasks-body', 'Tâche 3');
        });
    }
    /**
     * Test sorting tasks by all the sort criteria
     */
    public function testSortByOtherCriteria()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/tasks')
                ->waitFor('#tasks-body');

            $sortCriteria = [
                'participants_asc' => ['Tâche 1', 'Tâche 2', 'Tâche 3'],
                'participants_desc' => ['Tâche 3', 'Tâche 2', 'Tâche 1'],
                'beginDate_asc' => ['Tâche 2', 'Tâche 1', 'Tâche 3'],
                'endDate_desc' => ['Tâche 3', 'Tâche 2', 'Tâche 1'],
                'confirmed_asc' => ['Tâche 2', 'Tâche 3', 'Tâche 1'],
                'confirmed_desc' => ['Tâche 3', 'Tâche 2', 'Tâche 1'],
                'task_asc' => ['Tâche 1', 'Tâche 2', 'Tâche 3'],
                'task_desc' => ['Tâche 3', 'Tâche 2', 'Tâche 1']
            ];

            foreach ($sortCriteria as $sortOption => $expectedOrder) {

                $browser->select('#sort', $sortOption)
                    ->pause(100); // wait for the ajax request

                foreach ($expectedOrder as $task) {
                    $browser->assertSeeIn('#tasks-body', $task);
                }
            }
        });
    }
}
