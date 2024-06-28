<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QuoteController;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\APITokenMiddleware;

Route::prefix('quotes')->middleware(APITokenMiddleware::class)->group(function () {
    Route::get('/', [QuoteController::class, 'index']);
    Route::put('/refresh', [QuoteController::class, 'refresh']);
});
Route::post('/authenticate', [AuthController::class, 'authenticate']);
