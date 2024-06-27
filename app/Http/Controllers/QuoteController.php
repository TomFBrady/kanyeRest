<?php

namespace App\Http\Controllers;

use App\Http\Requests\BaseJsonRequest;
use App\Services\QuoteService;

class QuoteController extends Controller
{
    protected $quoteService;

    public function __construct(BaseJsonRequest $request, QuoteService $quoteService)
    {
        $this->quoteService = $quoteService;
    }

    public function index()
    {
        $quotes = $this->quoteService->getQuotes();

        return response()->json(['quotes' => $quotes]);
    }
}
