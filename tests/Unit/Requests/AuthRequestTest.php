<?php

namespace Tests\Unit;

use App\Http\Requests\AuthRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Routing\Redirector;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AuthRequestTest extends TestCase
{
    private function prepareRequest(array $data): AuthRequest
    {
        $request = AuthRequest::create('/', 'POST', $data);
        $request->headers->set('Accept', 'application/json');
        $request->setContainer($this->app)
            ->setRedirector(app(Redirector::class))
            ->validateResolved();

        return $request;
    }

    #[Test]
    public function itFailsValidationWithInvalidEmail(): void
    {
        $this->expectException(HttpResponseException::class);

        $data = ['email' => 'not-an-email', 'password' => 'testPassword123'];
        $request = $this->prepareRequest($data);
    }

    #[Test]
    public function itFailsValidationWithNoEmail(): void
    {
        $this->expectException(HttpResponseException::class);

        $data = ['password' => 'testPassword123'];
        $request = $this->prepareRequest($data);
    }

    #[Test]
    public function itFailsValidationWithInvalidPassword(): void
    {
        $this->expectException(HttpResponseException::class);

        $data = ['email' => 'test@test.com', 'password' => true];
        $request = $this->prepareRequest($data);
    }

    #[Test]
    public function itFailsValidationWithNoPassword(): void
    {
        $this->expectException(HttpResponseException::class);

        $data = ['email' => 'test@test.com'];
        $request = $this->prepareRequest($data);
    }

    #[Test]
    public function itPassesValidationWithValidData(): void
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
