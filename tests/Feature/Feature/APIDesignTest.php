<?php

namespace Tests\Feature\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Services\CommonService;
use App\Services\SignUpService;
use App\Services\LoginService;
use App\Models\User as User;
use App\Repositories\LoginRepository;

class APIDesignTest extends TestCase
{

    private $loginService;
    private $signUpService;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testRequestWithoutHeaders()
    {
        $expectedResponse = ['status' => 'error', 'message' => trans('messages.app_key_missing')];
        $response = $this->get('/api/api-status');
        $response->assertStatus(401);
        $response->assertJson($expectedResponse);
    }

    public function testRequestWithHeaders()
    {
        $headers = [
            'ShowBegins-APP-Key' => env('APP_KEY'),
            'ShowBegins-APP-Secret' => env('APP_SECRET'),
        ];
        $expectedResponse = ['status' => 'success', 'message' => trans('messages.api_success_status')];
        $response = $this->get('/api/api-status', $headers);
        $response->assertStatus(200);
        $response->assertJson($expectedResponse);
    }

    public function testRequestWithInvalidHeaders()
    {
        $headers = [
            'ShowBegins-APP-Key' => 'WRONG',
            'ShowBegins-APP-Secret' => 'WRONG',
        ];
        $expectedResponse = ['status' => 'error', 'message' => trans('messages.app_key_missing')];
        $response = $this->get('/api/api-status', $headers);
        $response->assertStatus(401);
        $response->assertJson($expectedResponse);
    }

    public function testWithoutSession()
    {
        $headers = [
            'ShowBegins-APP-Key' => env('APP_KEY'),
            'ShowBegins-APP-Secret' => env('APP_SECRET'),
        ];
        $expectedResponse = ['status' => 'error', 'message' => trans('messages.access_token_missing')];
        $response = $this->post('/api/session-check', [], $headers);
        $response->assertStatus(401);
        $response->assertJson($expectedResponse);
    }

    public function testWithInvalidSession()
    {
        $headers = [
            'ShowBegins-APP-Key' => env('APP_KEY'),
            'ShowBegins-APP-Secret' => env('APP_SECRET'),
            'access-token' => 'Bearer xxx'
        ];
        $expectedResponse = ['status' => 'error', 'message' => trans('messages.invalid_access_token')];
        $response = $this->post('/api/session-check', [], $headers);
        $response->assertStatus(401);
        $response->assertJson($expectedResponse);
    }

    public function testWithValidSession()
    {
        $signUpService = new SignUpService(new User());
        $loginService = new LoginService(new User(), new CommonService(), new LoginRepository());
        $email = Str::random(10) . '@gmail.com';
        $params = [
            'name' => Str::random(10),
            'email' => $email,
            'password' => CommonService::hashPassword('Password1'),
        ];

        $userId = $signUpService->signUpUser($params);
        $accessToken = $loginService->handleAccessTokens($userId);

        $expectedResponse = [
            'status' => 'success',
            'message' => trans('messages.valid_session'),
        ];
        $headers = [
            'ShowBegins-APP-Key' => env('APP_KEY'),
            'ShowBegins-APP-Secret' => env('APP_SECRET'),
            'access-token' => 'Bearer ' . $accessToken['access_token'],
        ];
        $response = $this->post('/api/session-check', [], $headers);
        $response->assertStatus(200);
        $response->assertJson($expectedResponse);
    }
}
