<?php

namespace Tests\Feature\Commands;

use Tests\TestCase;

class CreateUserTest extends TestCase
{
    public function test_the_command_creates_a_user(): void
    {
        $this->createTestUser();
        $this->assertDatabaseHas('users', ['email' => 'test@test.com']);
    }

    public function test_error_is_returned_if_user_already_exists(): void
    {
        $this->createTestUser();
        $this->artisan(
            'app:create-user',
            [
                'name' => 'John Doe',
                'email' => 'test@test.com',
                'password' => 'password'
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
                'password' => 'password'
            ]
        );
    }
}
