<?php

namespace Tests\Unit;

use App\Http\Requests\AuthRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Routing\Redirector;
use Tests\TestCase;

class AuthRequestTest extends TestCase
{
    private function prepareRequest(array $data): AuthRequest
    {
        $request = AuthRequest::create('/', 'POST', $data);
        $request->setContainer($this->app)
            ->setRedirector(app(Redirector::class))
            ->validateResolved();

        return $request;
    }

    /** @test */
    public function itFailsValidationWithInvalidEmail()
    {
        $this->expectException(HttpResponseException::class);

        $data = ['email' => 'not-an-email', 'password' => 'testPassword123'];
        $request = $this->prepareRequest($data);
    }

    /** @test */
    public function itFailsValidationWithNoEmail()
    {
        $this->expectException(HttpResponseException::class);

        $data = ['password' => 'testPassword123'];
        $request = $this->prepareRequest($data);
    }

    /** @test */
    public function itFailsValidationWithInvalidPassword()
    {
        $this->expectException(HttpResponseException::class);

        $data = ['email' => 'test@test.com', 'password' => true];
        $request = $this->prepareRequest($data);
    }

    /** @test */
    public function itFailsValidationWithNoPassword()
    {
        $this->expectException(HttpResponseException::class);

        $data = ['email' => 'test@test.com'];
        $request = $this->prepareRequest($data);
    }

    /** @test */
    public function itPassesValidationWithValidData()
    {
        $data = ['email' => 'test@example.com', 'password' => 'validPassword123'];

        try {
            $request = $this->prepareRequest($data);
            $this->assertTrue(true); // Dummy assertion to pass the test, test failure is detirmined by exception thrown
        } catch (HttpResponseException $e) {
            $this->fail('Validation should not fail with valid data.');
        }
    }
}
