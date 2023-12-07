<?php

namespace Tests\Unit;

use Tests\TestCase;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskControllerTest extends TestCase{
    use RefreshDatabase;
     
    public function test_addition_of_invalid_task_null_description()
    {
        $taskdata=[
            'name'=>"Faire mes taches",
            'duration'=>10,
            'people_count'=>5,
            'address'=>'rue royal ,6 1000 Bruxelles'
        ];

        $this->actingAs(User::find(1))->post(route('tasks.store'),$taskdata);
        $this->assertDatabaseMissing('tasks',[
            'name'=>"Faire mes taches"
        ]);
    }

    public function test_addition_of_invalid_task_null_duration():void 
    {
        $taskdata=[
            'name'=>"Faire mes taches",
            'description'=>"finir taches",
            'people_count'=>5,
            'address'=>'rue royal ,6 1000 Bruxelles'
        ];

        $this->actingAs(User::find(1))->post(route('tasks.store'),$taskdata);
        $this->assertDatabaseMissing('tasks',[
            'name'=>"Faire mes taches"
        ]);
    }

    public function test_addition_of_invalid_task_null_people_count():void 
    {
        $taskdata=[
            'name'=>"Faire mes taches",
            'duration'=>10,
            'description'=>"finir taches",
            'address'=>'rue royal ,6 1000 Bruxelles'
        ];

        $this->actingAs(User::find(1))->post(route('tasks.store'),$taskdata);
        $this->assertDatabaseMissing('tasks',[
            'name'=>"Faire mes taches"
        ]);
    }

    public function test_addition_of_invalid_task_null_people_address():void 
    {
        $taskdata=[
            'name'=>"Faire mes taches",
            'duration'=>10,
            'description'=>"finir taches",
            'people_count'=>5,
        ];

        $this->actingAs(User::find(1))->post(route('tasks.store'),$taskdata);
        $this->assertDatabaseMissing('tasks',[
            'name'=>"Faire mes taches"
        ]);
    }

    public function test_addition_of_valid_task():void 
    {
        $taskdata=[
            'name'=>"Faire mes taches",
            'description'=>"finir taches",
            'people_count'=>5,
            'people_min'=>3,
            'people_max'=>5,
            'start_datetime'=> now()->addHour(),
            'end_datetime'=> now()->addHours(2),
            'address'=>'rue royal ,6 1000 Bruxelles'
        ];

        $this->actingAs(User::find(1))->post(route('tasks.store'),$taskdata);
        $this->assertDatabasehas('tasks',[
            'name'=>"Faire mes taches"
        ]);
    }

    public function test_addition_form_creat_task_return_code():void
    {
        $taskdata=[
            'name'=>"Faire mes taches",
            'duration'=>10,
            'description'=>"finir taches",
            'people_count'=>5,
            'address'=>'rue royal ,6 1000 Bruxelles'
        ];

        $response=$this->actingAs(User::find(1))->post(route('tasks.store'),$taskdata);
        
        
        $response->assertStatus(302);
    }

    public function test_consulting_form_creat_task_return_code():void
    {
        $response=$this->actingAs(User::find(1))->get('/create');
        $response->assertStatus(200);
    }



}
