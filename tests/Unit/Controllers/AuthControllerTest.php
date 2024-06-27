<?php

namespace Tests\Unit;

use App\Http\Controllers\AuthController;
use App\Http\Requests\AuthRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    #[Test]
    public function itRespondsWithTokenOnSuccessfulAuthentication()
    {
        $request = $this->createMock(AuthRequest::class);
        $request->expects($this->once())
            ->method('only')
            ->with('email', 'password')
            ->willReturn(['email' => 'user@example.com', 'password' => 'password']);

        Auth::shouldReceive('attempt')
            ->once()
            ->with(['email' => 'user@example.com', 'password' => 'password'])
            ->andReturn(true);

        $user = $this->createMock(User::class);
        $user->expects($this->once())
            ->method('generateApiToken')->willReturn('testApiToken');

        $request->expects($this->once())
            ->method('user')
            ->willReturn($user);

        $controller = new AuthController();
        $response = $controller->authenticate($request);

        $this->assertEquals($response->status(), 200);
        $this->assertEquals($response->content(), json_encode(['apiToken' => 'testApiToken']));
    }

    #[Test]
    public function itRespondsWithUnauthenticatedOnFailedAuthentication()
    {
        $request = $this->createMock(AuthRequest::class);
        $request->expects($this->once())
            ->method('only')
            ->willReturn(['email' => 'wrong@example.com', 'password' => 'wrongpassword']);

        Auth::shouldReceive('attempt')
            ->once()
            ->andReturn(false);

        $controller = new AuthController();
        $response = $controller->authenticate($request);

        $this->assertEquals($response->status(), 401);
        $this->assertEquals($response->content(), json_encode(['message' => 'Unauthenticated']));
    }
}
