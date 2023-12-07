<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserVerifyTest extends TestCase
{
    use RefreshDatabase;
    public function test_unverified_user_gets_redirected(): void
    {
        $user = User::factory()->unverified()->create();
        $this->actingAs($user)
                ->get(route("tasks.index"))
                ->assertStatus(403)
                ->assertViewIs("auth.unverified");
    }

    public function test_verified_user_gets_where_they_want(): void
    {
        $user = User::factory()->create();
        $user = User::find($user->id);
        $this->actingAs($user)
                ->get(route("tasks.index"))
                ->assertOk()
                ->assertViewIs("tasks.show_task");
    }

    public function test_admin_can_see_user_verification_page(): void
    {
        $user = User::find(1);
        $user = User::find($user->id);
        $this->actingAs($user)
                ->get(route("verify"))
                ->assertOk()
                ->assertViewIs("auth.validate");
    }

    public function test_non_admin_can_see_user_verification_page(): void
    {
        $user = User::factory()->create();
        $user = User::find($user->id);
        $this->actingAs($user)
                ->get(route("verify"))
                ->assertStatus(404);
    }
    public function test_admin_accepts_new_user(): void
    {
        $user = User::factory()->unverified()->create();
        $user = User::find($user->id);
        $admin = User::find(1);

        $this->assertFalse((boolean) $user->is_verified);

        $this->actingAs($admin)
            ->patch(route("verify.add"), ['id_user_unverified' => $user->id]);
        
        $user = User::find($user->id);
        $this->assertTrue((boolean) $user->is_verified);
    }

    public function test_admin_refuses_new_user(): void
    {
        $user = User::factory()->unverified()->create();
        $admin = User::find(1);

        $this->actingAs($admin)
            ->delete(route("verify.delete"), ['id_user_unverified' => $user->id]);
        
        $this->assertDatabaseMissing('users', ['id'=> $user->id]);
    }
}
