<?php

namespace App\Http\Controllers;

use App\Http\Requests\BaseJsonRequest;
use App\Services\QuoteService;
use Illuminate\Http\JsonResponse;

class QuoteController extends Controller
{
    protected $quoteService;

    public function __construct(BaseJsonRequest $request, QuoteService $quoteService)
    {
        $this->quoteService = $quoteService;
    }

    public function index(): JsonResponse
    {
        $quotes = $this->quoteService->getQuotes();

        return response()->json(['quotes' => $quotes]);
    }

    public function refresh(): void
    {
        $this->quoteService->invalidateCache();
    }
}
