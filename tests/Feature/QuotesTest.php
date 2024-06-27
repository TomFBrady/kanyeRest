<?php

namespace Tests\Feature;

use Tests\TestCase;

class QuotesTest extends TestCase
{
    public function testTheApplicationReturnsASuccessfulResponse(): void
    {
        $response = $this->get('/api/quotes');

        $response->assertStatus(200);
        $response->assertJsonFragment(['Endpoint hit!']);
    }
}
