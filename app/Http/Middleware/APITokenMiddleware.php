<?php

namespace App\Http\Middleware;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class APITokenMiddleware
{
    public function handle(Request $request, \Closure $next): JsonResponse
    {
        $token = $request->bearerToken();

        if (!$token) {
            return $this->handleUnauthenticatedRequest();
        }

        $user = User::where('api_token', $token)->first();

        if (!$user) {
            return $this->handleUnauthenticatedRequest();
        }

        Auth::login($user);

        return $next($request);
    }

    private function handleUnauthenticatedRequest(): JsonResponse
    {
        return response()->json(['message' => 'Unauthenticated'], 401);
    }
}
