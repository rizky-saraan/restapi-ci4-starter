<?php

namespace App;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;
use CodeIgniter\Test\ControllerTestTrait;

class TestAuth extends CIUnitTestCase
{
    use FeatureTestTrait;

    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    //method yang ada didalam controller auth
    public function testLogin()
    {
        $headers = [
            'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0OjgwODAvYXBpL2xvZ2luIiwiaWF0IjoxNjczNjI1ODkwLCJleHAiOjE2NzM3MTIyOTAsIm5iZiI6MTY3MzYyNTg5MCwianRpIjoxNjczNjI1ODkwfQ.U3slamHCx37xd3JK5eVDFg7CAP5ryaqoMBeyf5U_2zU',
        ];

        // Get a simple page
        $routes = [
            ['post', 'api/login', 'Api\AuthController::login'],
        ];

        $params = json_encode([
            'username'  => 'ahmad',
            'password' => '123123123',
        ]);

        $result = $this
            ->withHeaders($headers)
            ->withBodyFormat('json')
            ->withBody($params)
            ->withRoutes($routes)
            ->post('api/login');
        $result->assertStatus(409);
    }
}
