<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class QuotesTest extends TestCase
{
    use RefreshDatabase;

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
        Http::fake([
            'https://api.kanye.rest/' => Http::response(['quote' => 'I am kanye'], 200),
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer testApiToken',
            'Accept' => 'application/json',
        ])->get('/api/quotes');

        $response->assertStatus(200);
        $response->assertJson(
            [
                'quotes' => [
                    ['quote' => 'I am kanye'],
                    ['quote' => 'I am kanye'],
                    ['quote' => 'I am kanye'],
                    ['quote' => 'I am kanye'],
                    ['quote' => 'I am kanye'],
                ],
            ]
        );
    }

    public function testTheApplicationReturnsAnUnauthenticatedResponseWithNoBearer(): void
    {
        $response = $this->withHeaders(['Accept' => 'application/json'])->get('/api/quotes');

        $response->assertStatus(401);
        $response->assertJsonFragment(['Unauthenticated']);
    }

    public function testTheApplicationReturnsAnUnauthenticatedResponseWithIncorrectBearer(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer wrongBearer',
            'Accept' => 'application/json',
        ])->get('/api/quotes');

        $response->assertStatus(401);
        $response->assertJsonFragment(['Unauthenticated']);
    }
}
