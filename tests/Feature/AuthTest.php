<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class AuthTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        User::create([
            'name' => 'Test User',
            'email' => 'test@test.com',
            'password' => 'password',
            'api_token' => '',
        ]);
    }

    public function testApiTokenGeneratedAndReturnedOnSuccessfulAuthentication(): void
    {
        $response = $this->postJson('/api/authenticate', [
            'email' => 'test@test.com',
            'password' => 'password',
        ]);

        $user = User::where('email', 'test@test.com')->first();

        $response->assertStatus(200);
        $response->assertJson(['apiToken' => $user->api_token]);
    }

    public function testApiTokenNotGeneratedAndReturnedOnUnsuccessfulAuthenticationAttempt(): void
    {
        $response = $this->postJson('/api/authenticate', [
            'email' => 'test@test.com',
            'password' => 'wrongPassword',
        ]);

        $user = User::where('email', 'test@test.com')->first();

        $response->assertStatus(401);
        $this->assertEquals($user->api_token, '');
    }
}
