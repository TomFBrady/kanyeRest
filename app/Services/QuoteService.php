<?php

namespace App\Services;

use App\Http\Exceptions\QuoteRetrievalException;
use Illuminate\Http\Client\Pool;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;

class QuoteService
{
    private $url;
    private $numberOfQuotes;
    private $cacheTTL;

    public function __construct()
    {
        $this->url = Config::get('services.kanyeRestUrl');
        $this->numberOfQuotes = Config::get('app.numberOfQuotes');
        $this->cacheTTL = Config::get('app.cacheTTL');
    }

    public function getQuotes(): Collection
    {
        try {
            $quotes = Cache::remember('quotes', $this->cacheTTL, function () {
                $responses = $this->fetchQuotes();
                $quotes = $this->validatAndFormatResponses($responses);

                return $quotes;
            });

            return $quotes;
        } catch (\Throwable $e) {
            throw new QuoteRetrievalException('Failed to fetch quotes');
        }
    }

    private function fetchQuotes(): Collection
    {
        return Collect(Http::pool(function (Pool $pool) {
            return collect()
                ->range(1, $this->numberOfQuotes)
                ->map(fn () => $pool->get($this->url));
        }));
    }

    private function validatAndFormatResponses(Collection $responses): Collection
    {
        $quotes = collect();
        foreach ($responses as $response) {
            if ($response->successful()) {
                $quoteData = $response->json();
                $quotes->push($quoteData);
            } else {
                throw new \Exception();
            }
        }

        return $quotes;
    }

    public function invalidateCache(): void
    {
        Cache::forget('quotes');
    }
}
