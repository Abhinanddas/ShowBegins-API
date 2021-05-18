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

class APIDesignTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testRequestWithoutHeaders()
    {
        $expectedResponse = ['status' => 'error', 'msg' => trans('messages.app_key_missing')];
        $response = $this->post('/api/api-status');
        $response->assertStatus(401);
        $response->assertJson($expectedResponse);
    }

    public function testRequestWithHeaders()
    {
        $headers = [
            'ShowBegins-APP-Key' => 'base64:S2wgFrGsp81CHpMbtKV6dMjAcFakrV5b1qWPzNG5+ss=',
            'ShowBegins-APP-Secret' => 'SHOW_BEGINS_APP_SECRET',
        ];
        $expectedResponse = ['status' => 'success', 'msg' => trans('messages.api_success_status')];
        $response = $this->post('/api/api-status', [], $headers);
        $response->assertStatus(200);
        $response->assertJson($expectedResponse);
    }

    public function testRequestWithInvalidHeaders()
    {
        $headers = [
            'ShowBegins-APP-Key' => 'WRONG',
            'ShowBegins-APP-Secret' => 'WRONG',
        ];
        $expectedResponse = ['status' => 'error', 'msg' => trans('messages.app_key_missing')];
        $response = $this->post('/api/api-status', [], $headers);
        $response->assertStatus(401);
        $response->assertJson($expectedResponse);
    }

    public function testWithoutSession()
    {
        $headers = [
            'ShowBegins-APP-Key' => 'base64:S2wgFrGsp81CHpMbtKV6dMjAcFakrV5b1qWPzNG5+ss=',
            'ShowBegins-APP-Secret' => 'SHOW_BEGINS_APP_SECRET',
        ];
        $expectedResponse = ['status' => 'error', 'msg' => trans('messages.access_token_missing')];
        $response = $this->post('/api/session-check', [], $headers);
        $response->assertStatus(401);
        $response->assertJson($expectedResponse);
    }

    public function testWithInvalidSession()
    {
        $headers = [
            'ShowBegins-APP-Key' => 'base64:S2wgFrGsp81CHpMbtKV6dMjAcFakrV5b1qWPzNG5+ss=',
            'ShowBegins-APP-Secret' => 'SHOW_BEGINS_APP_SECRET',
            'access-token' => 'Bearer xxx'
        ];
        $expectedResponse = ['status' => 'error', 'msg' => trans('messages.invalid_access_token')];
        $response = $this->post('/api/session-check', [], $headers);
        $response->assertStatus(401);
        $response->assertJson($expectedResponse);
    }

    public function testWithValidSession(SignUpService $signUpService, LoginService $loginService)
    {
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
            'msg' => trans('messages.valid_session'),
        ];
        $headers = [
            'ShowBegins-APP-Key' => 'base64:S2wgFrGsp81CHpMbtKV6dMjAcFakrV5b1qWPzNG5+ss=',
            'ShowBegins-APP-Secret' => 'SHOW_BEGINS_APP_SECRET',
            'access-token' => 'Bearer ' . $accessToken['access_token'],
        ];
        $response = $this->post('/api/session-check', [], $headers);
        $response->assertStatus(200);
        $response->assertJson($expectedResponse);
    }
}
