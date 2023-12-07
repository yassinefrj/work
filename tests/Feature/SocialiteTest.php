<?php

namespace Tests\Feature;
use App\Providers\RouteServiceProvider;
use Mockery;
use Tests\TestCase;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SocialiteTest extends TestCase
{
    
    use RefreshDatabase;

    /**
     * A basic test example.
     */
    public function test_google_auth(): void
    {

        $user = Mockery::mock('Laravel\Socialite\Contracts\User');
        
        $user->shouldReceive('getId')->andReturn('12345');
        $user->shouldReceive('getName')->andReturn('Chris Willerton');
        $user->shouldReceive('getEmail')->andReturn('hello@gmail.com');

        $provider = Mockery::mock('Laravel\Socialite\Contracts\Provider');
        $provider->shouldReceive('user')->andReturn($user);

        Socialite::shouldReceive('driver')->with('google')->andReturn($provider);

        $this->get('/auth/google/callback')->assertRedirect(RouteServiceProvider::HOME);

        $this->assertDatabaseHas('users', ['email' => 'hello@gmail.com', 'google_id' => '12345']);
        
    }

}
