<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();

        // Desativa middleware CSRF
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);
    }
}
