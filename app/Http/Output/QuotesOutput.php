<?php

namespace App\Http\Output;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

class QuotesOutput
{
    public static function format(Collection $quotes): JsonResponse
    {
        return response()->json(['quotes' => $quotes->map(fn ($response) => $response->json())]);
    }
}
