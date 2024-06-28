<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\Http;
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

    public function testTheApplicationCachesQuotes(): void
    {
        Http::fake([
            'https://api.kanye.rest/' => Http::response(['quote' => 'I am kanye'], 200),
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer testApiToken',
            'Accept' => 'application/json',
        ])->get('/api/quotes');

        Http::fake([
            'https://api.kanye.rest/' => Http::response(['quote' => 'I am not kanye'], 200),
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

    public function testTheRefreshQuotesRouteSuccessful(): void
    {
        Http::fake([
            'https://api.kanye.rest/' => Http::sequence()
                ->push(['quote' => 'I am kanye'], 200)
                ->push(['quote' => 'I am kanye'], 200)
                ->push(['quote' => 'I am kanye'], 200)
                ->push(['quote' => 'I am kanye'], 200)
                ->push(['quote' => 'I am kanye'], 200)
                ->push(['quote' => 'I am not kanye'], 200)
                ->push(['quote' => 'I am not kanye'], 200)
                ->push(['quote' => 'I am not kanye'], 200)
                ->push(['quote' => 'I am not kanye'], 200)
                ->push(['quote' => 'I am not kanye'], 200),
        ]);
        $firstResponse = $this->withHeaders([
            'Authorization' => 'Bearer testApiToken',
            'Accept' => 'application/json',
        ])->get('/api/quotes');

        $refresh = $this->withHeaders([
            'Authorization' => 'Bearer testApiToken',
            'Accept' => 'application/json',
        ])->put('/api/quotes/refresh');

        $secondResponse = $this->withHeaders([
            'Authorization' => 'Bearer testApiToken',
            'Accept' => 'application/json',
        ])->get('/api/quotes');

        $refresh->assertStatus(200);
        $firstResponse->assertStatus(200);
        $firstResponse->assertJson(
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

        $secondResponse->assertStatus(200);
        $secondResponse->assertJson(
            [
                'quotes' => [
                    ['quote' => 'I am not kanye'],
                    ['quote' => 'I am not kanye'],
                    ['quote' => 'I am not kanye'],
                    ['quote' => 'I am not kanye'],
                    ['quote' => 'I am not kanye'],
                ],
            ]
        );
    }
}
