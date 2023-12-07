<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class SortTaskTest extends TestCase
{

    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /**
     * TEST FOR SORT BY NAME
     */

    public function test_sort_task_by_name_asc()
    {
        $user = User::find(1);
        $this->actingAs($user);
        $response = $this->call('POST', '/tasks/sort', ['sortBy' => 'task_asc']);
        $responseData = $response->json();
        $this->assertSortedByNameAsc(json_encode($responseData));
    }

    public function test_sort_task_by_name_desc()
    {
        $user = User::find(1);
        $this->actingAs($user);
        $response = $this->call('POST', '/tasks/sort', ['sortBy' => 'task_desc']);
        $responseData = $response->json();
        $this->assertSortedByNameDesc(json_encode($responseData));
    }

    private function assertSortedByNameAsc($jsonResponse)
    {
        $tasks = json_decode($jsonResponse, true);
        $taskCount = count($tasks);
        $sorted = true;
        for ($i = 0; $i < $taskCount - 1; $i++) {
            if ($tasks[$i]['name'] > $tasks[$i + 1]['name']) {
                $sorted = false;
                break;
            }
        }
        $this->assertTrue($sorted);
    }

    private function assertSortedByNameDesc($jsonResponse)
    {
        $tasks = json_decode($jsonResponse, true);
        $taskCount = count($tasks);
        $sorted = true;
        for ($i = 0; $i < $taskCount - 1; $i++) {
            if ($tasks[$i]['name'] < $tasks[$i + 1]['name']) {
                $sorted = false;
                break;
            }
        }
        $this->assertTrue($sorted);
    }

    /**
     * TEST FOR SORT BY participants
     */

    public function test_sort_task_by_participants_asc()
    {
        $user = User::find(1);
        $this->actingAs($user);
        $response = $this->call('POST', '/tasks/sort', ['sortBy' => 'participants_asc']);
        $responseData = $response->json();
        $this->assertSortedByParticipantsAsc(json_encode($responseData));
    }

    public function test_sort_task_by_participants_desc()
    {
        $user = User::find(1);
        $this->actingAs($user);
        $response = $this->call('POST', '/tasks/sort', ['sortBy' => 'participants_desc']);
        $responseData = $response->json();
        $this->assertSortedByParticipantsDesc(json_encode($responseData));
    }

    private function assertSortedByParticipantsAsc($jsonResponse)
    {
        $tasks = json_decode($jsonResponse, true);
        $taskCount = count($tasks);
        $sorted = true;
        for ($i = 0; $i < $taskCount - 1; $i++) {
            if ($tasks[$i]['people_count'] > $tasks[$i + 1]['people_count']) {
                $sorted = false;
                break;
            }
        }
        $this->assertTrue($sorted);
    }

    private function assertSortedByParticipantsDesc($jsonResponse)
    {
        $tasks = json_decode($jsonResponse, true);
        $taskCount = count($tasks);
        $sorted = true;
        for ($i = 0; $i < $taskCount - 1; $i++) {
            if ($tasks[$i]['people_count'] < $tasks[$i + 1]['people_count']) {
                $sorted = false;
                break;
            }
        }
        $this->assertTrue($sorted);
    }

    /**
     * TEST FOR SORT BY BEGIN DATE
     */

    public function test_sort_task_by_begin_date_asc()
    {
        $user = User::find(1);
        $this->actingAs($user);
        $response = $this->call('POST', '/tasks/sort', ['sortBy' => 'beginDate_asc']);
        $responseData = $response->json();
        $this->assertSortedByBeginDateAsc(json_encode($responseData));
    }

    public function test_sort_task_by_begin_date_desc()
    {
        $user = User::find(1);
        $this->actingAs($user);
        $response = $this->call('POST', '/tasks/sort', ['sortBy' => 'beginDate_desc']);
        $responseData = $response->json();
        $this->assertSortedByBeginDateDesc(json_encode($responseData));
    }

    private function assertSortedByBeginDateAsc($jsonResponse)
    {
        $tasks = json_decode($jsonResponse, true);
        $taskCount = count($tasks);
        $sorted = true;
        for ($i = 0; $i < $taskCount - 1; $i++) {
            if ($tasks[$i]['start_datetime'] > $tasks[$i + 1]['start_datetime']) {
                $sorted = false;
                break;
            }
        }
        $this->assertTrue($sorted);
    }

    private function assertSortedByBeginDateDesc($jsonResponse)
    {
        $tasks = json_decode($jsonResponse, true);
        $taskCount = count($tasks);
        $sorted = true;
        for ($i = 0; $i < $taskCount - 1; $i++) {
            if ($tasks[$i]['start_datetime'] < $tasks[$i + 1]['start_datetime']) {
                $sorted = false;
                break;
            }
        }
        $this->assertTrue($sorted);
    }

    /**
     * TEST FOR SORT BY END DATE
     */

    public function test_sort_task_by_end_date_asc()
    {
        $user = User::find(1);
        $this->actingAs($user);
        $response = $this->call('POST', '/tasks/sort', ['sortBy' => 'endDate_asc']);
        $responseData = $response->json();
        $this->assertSortedByEndDateAsc(json_encode($responseData));
    }

    public function test_sort_task_by_end_date_desc()
    {
        $user = User::find(1);
        $this->actingAs($user);
        $response = $this->call('POST', '/tasks/sort', ['sortBy' => 'endDate_desc']);
        $responseData = $response->json();
        $this->assertSortedByEndDateDesc(json_encode($responseData));
    }

    private function assertSortedByEndDateAsc($jsonResponse)
    {
        $tasks = json_decode($jsonResponse, true);
        $taskCount = count($tasks);
        $sorted = true;
        for ($i = 0; $i < $taskCount - 1; $i++) {
            if ($tasks[$i]['end_datetime'] > $tasks[$i + 1]['end_datetime']) {
                $sorted = false;
                break;
            }
        }
        $this->assertTrue($sorted);
    }

    private function assertSortedByEndDateDesc($jsonResponse)
    {
        $tasks = json_decode($jsonResponse, true);
        $taskCount = count($tasks);
        $sorted = true;
        for ($i = 0; $i < $taskCount - 1; $i++) {
            if ($tasks[$i]['end_datetime'] < $tasks[$i + 1]['end_datetime']) {
                $sorted = false;
                break;
            }
        }
        $this->assertTrue($sorted);
    }

    /**
     * TEST FOR SORT BY ADRESS
     */

    public function test_sort_task_by_address_asc()
    {
        $user = User::find(1);
        $this->actingAs($user);
        $response = $this->call('POST', '/tasks/sort', ['sortBy' => 'address_asc']);
        $responseData = $response->json();
        $this->assertSortedByAddressAsc(json_encode($responseData));
    }

    public function test_sort_task_by_address_desc()
    {
        $user = User::find(1);
        $this->actingAs($user);
        $response = $this->call('POST', '/tasks/sort', ['sortBy' => 'address_desc']);
        $responseData = $response->json();
        $this->assertSortedByAddressDesc(json_encode($responseData));
    }

    private function assertSortedByAddressAsc($jsonResponse)
    {
        $tasks = json_decode($jsonResponse, true);
        $taskCount = count($tasks);
        $sorted = true;
        for ($i = 0; $i < $taskCount - 1; $i++) {
            if ($tasks[$i]['address'] > $tasks[$i + 1]['address']) {
                $sorted = false;
                break;
            }
        }
        $this->assertTrue($sorted);
    }

    private function assertSortedByAddressDesc($jsonResponse)
    {
        $tasks = json_decode($jsonResponse, true);
        $taskCount = count($tasks);
        $sorted = true;
        for ($i = 0; $i < $taskCount - 1; $i++) {
            if ($tasks[$i]['address'] < $tasks[$i + 1]['address']) {
                $sorted = false;
                break;
            }
        }
        $this->assertTrue($sorted);
    }

    /**
     * TEST FOR SORT BY Inscription
     */

    public function test_sort_task_by_inscription_asc()
    {
        $user = User::find(1);
        $this->actingAs($user);
        $response = $this->call('POST', '/tasks/sort', ['sortBy' => 'inscription_asc']);
        $responseData = $response->json();

        $this->assertSortedByInscriptionAsc(json_encode($responseData));
    }

    public function test_sort_task_by_inscription_desc()
    {
        $user = User::find(1);
        $this->actingAs($user);
        $response = $this->call('POST', '/tasks/sort', ['sortBy' => 'inscription_desc']);
        $responseData = $response->json();

        $this->assertSortedByInscriptionDesc(json_encode($responseData));
    }
   
    private function assertSortedByInscriptionAsc($jsonResponse)
    {
        $tasks = json_decode($jsonResponse, true);
        $taskCount = count($tasks);
        $sorted = true;

        $state = 'Inscrit';
        $basculeCount = 0;
        for ($i = 0; $i < $taskCount - 1; $i++) {
            if($basculeCount>1){
                $sorted =false;
                break;
            } else if ($tasks[$i]['StatusInscription'] == $state && $tasks[$i+1]['StatusInscription'] == $state ) {
                $sorted = true;
            } else {
                $state = 'Non Inscrit';
                $basculeCount++;
            }
        }
        $this->assertTrue($sorted);
    }

    private function assertSortedByInscriptionDesc($jsonResponse)
    {
        $tasks = json_decode($jsonResponse, true);
        $taskCount = count($tasks);
        $sorted = true;

        $state = 'Non Inscrit';
        $basculeCount = 0;
        for ($i = 0; $i < $taskCount - 1; $i++) {
            if($basculeCount>1){
                $sorted =false;
                break;
            } else if ($tasks[$i]['StatusInscription'] == $state && $tasks[$i+1]['StatusInscription'] == $state ) {
                $sorted = true;
            } else {
                $state = 'Inscrit';
                $basculeCount++;
            }
        }
        $this->assertTrue($sorted);
    }    
}