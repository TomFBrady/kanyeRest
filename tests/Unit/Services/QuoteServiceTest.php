<?php

namespace Tests\Unit;

use App\Http\Exceptions\QuoteRetrievalException;
use App\Services\QuoteService;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class QuoteServiceTest extends TestCase
{
    #[Test]
    public function itRetrievesQuotesSuccessfully()
    {
        Http::fake([
            'https://api.kanye.rest/' => Http::response(['quote' => 'kanye quote'], 200),
        ]);

        $service = new QuoteService();
        $quotes = $service->getQuotes();

        $this->assertCount(5, $quotes);
        $this->assertEquals('kanye quote', $quotes->first()['quote']);
    }

    #[Test]
    public function itThrowsAnExceptionWhenAQuoteRetrievalFails()
    {
        Http::fake([
            'https://api.kanye.rest/' => Http::sequence()
                ->push(['quote' => 'kanye quote'], 200)
                ->push('', 500),
        ]);

        $this->expectException(QuoteRetrievalException::class);
        $this->expectExceptionMessage('Failed to fetch quotes');

        $service = new QuoteService();
        $service->getQuotes();
    }
}
