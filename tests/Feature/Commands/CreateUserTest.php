<?php

namespace Tests\Feature\Commands;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateUserTest extends TestCase
{
    use RefreshDatabase;

    public function testTheCommandCreatesAUser(): void
    {
        $this->createTestUser();
        $this->assertDatabaseHas('users', ['email' => 'test@test.com']);
    }

    public function testErrorIsReturnedIfUserAlreadyExists(): void
    {
        $this->createTestUser();
        $this->artisan(
            'app:create-user',
            [
                'name' => 'John Doe',
                'email' => 'test@test.com',
                'password' => 'password',
            ]
        )->expectsOutput('User already exists');
        $this->assertDatabaseCount('users', 1);
    }

    private function createTestUser(): void
    {
        $this->artisan(
            'app:create-user',
            [
                'name' => 'John Doe',
                'email' => 'test@test.com',
                'password' => 'password',
            ]
        );
    }
}
