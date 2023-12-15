<?php

namespace Tests\Feature;

use App\Models\User;
use App\Http\Controllers\UserController;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_is_exist_if_the_current_user_is_admin(): void
    {
        $response = $this->actingAs(User::find(1))->get('/user/1');      
        $response->assertSee("admin");
    }

    public function test_user_is_not_exist_if_the_current_user_is_admin(): void
    {
        $response = $this->actingAs(User::find(1))->get('/user/9');      
        $response->assertStatus(404);
    }

    public function test_user_is_exist_if_the_current_user_is_a_user(): void
    {
        $response = $this->actingAs(User::find(2))->get('/user/1');      
        $response->assertStatus(403);
    }
}