<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function authenticate(AuthRequest $request)
    {
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {

            return response()->json(['apiToken' => $request->user()->generateApiToken()], 200);
        }
        return response()->json(['message' => 'Unauthenticated'], 401);
    }
}
