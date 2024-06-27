<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class QuotesTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        User::create([
            'name' => 'Test User',
            'email' => 'test@test.com',
            'password' => 'password',
            'api_token' => 'testApiToken',
        ]);
    }

    public function testTheApplicationReturnsASuccessfulResponse(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer testApiToken',
        ])->get('/api/quotes');

        $response->assertStatus(200);
        $response->assertJsonFragment(['Endpoint hit!']);
    }

    public function testTheApplicationReturnsAnUnauthenticatedResponseWithNoBearer(): void
    {
        $response = $this->get('/api/quotes');

        $response->assertStatus(401);
        $response->assertJsonFragment(['Unauthenticated']);
    }

    public function testTheApplicationReturnsAnUnauthenticatedResponseWithIncorrectBearer(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer wrongBearer',
        ])->get('/api/quotes');

        $response->assertStatus(401);
        $response->assertJsonFragment(['Unauthenticated']);
    }
}
