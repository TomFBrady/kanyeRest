<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QuoteController;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\APITokenMiddleware;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware(APITokenMiddleware::class)->group(function () {
    Route::get('/quotes', [QuoteController::class, 'index']);
});
Route::post('/authenticate', [AuthController::class, 'authenticate']);
