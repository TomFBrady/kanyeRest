<?php

namespace Tests\Unit;

use App\Http\Exceptions\QuoteRetrievalException;
use App\Services\QuoteService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class QuoteServiceTest extends TestCase
{
    #[Test]
    public function itRetrievesQuotesSuccessfully(): void
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
    public function itThrowsAnExceptionWhenAQuoteRetrievalFails(): void
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

    #[Test]
    public function itCachesQuotesSuccessfully(): void
    {
        Cache::add('quotes', collect(['quote' => 'cached quote']), 600);
        Http::fake([
            'https://api.kanye.rest/' => Http::response(['quote' => 'kanye quote'], 200),
        ]);

        $service = new QuoteService();
        $quotes = $service->getQuotes();

        $this->assertCount(1, $quotes);
        $this->assertEquals('cached quote', $quotes->first());
    }

    #[Test]
    public function itInvalidatesCacheCorrectly(): void
    {
        Cache::add('quotes', collect(['quote' => 'cached quote']), 600);
        Http::fake([
            'https://api.kanye.rest/' => Http::response(['quote' => 'kanye quote'], 200),
        ]);

        $service = new QuoteService();
        $quotes = $service->getQuotes();

        $this->assertCount(1, $quotes);
        $this->assertEquals('cached quote', $quotes->first());
        $service->invalidateCache();
        $quotes = $service->getQuotes();
        $this->assertCount(5, $quotes);
        $this->assertEquals('kanye quote', $quotes->first()['quote']);
    }
}
