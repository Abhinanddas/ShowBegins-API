<?php

namespace Tests\Feature\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginTest extends TestCase
{

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testLogin()
    {

        $headers = [
            'ShowBegins-APP-Key' => 'base64:S2wgFrGsp81CHpMbtKV6dMjAcFakrV5b1qWPzNG5+ss=',
            'ShowBegins-APP-Secret' => 'SHOW_BEGINS_APP_SECRET',
        ];
        $this->json('POST', '/api/login',[],$headers)
            ->assertStatus(200);
    }
}
