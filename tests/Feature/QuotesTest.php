<?php

namespace Tests\Feature;

use Tests\TestCase;

class QuotesTest extends TestCase
{
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/api/quotes');

        $response->assertStatus(200);
        $response->assertJsonFragment(['Endpoint hit!']);
    }
}
