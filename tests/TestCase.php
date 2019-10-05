<?php

namespace Tests;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Cache;
use App\Models\User;


abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, DatabaseMigrations, DatabaseTransactions;

    private $token = '';

    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('passport:install');

        $response = $this->post(route('auth.signin'), [
            'email' => config('internal_settings.admin.email'),
            'password' => config('internal_settings.admin.password')
        ]);

        $content = $response->getOriginalContent();
        $this->token = $content['access_token'];
    }

    public function tearDown(): void
    {
        $this->token = '';
        $this->post(route('auth.logout'));

        $this->artisan('migrate:reset');
        parent::tearDown();
    }

    protected function makeHeaders(string $method, bool $withAuth = true): array
    {
        $headers = [
            'Accept' => 'application/json',
        ];

        switch ($method) {
            case 'GET':
                $headers['Content-Type'] = 'application/json';
                break;
            default:
                break;
        }

        if ($withAuth) {
            $headers['Authorization'] = "Bearer {$this->token}";
        }

        // dd($headers);

        return $headers;
    }

}
