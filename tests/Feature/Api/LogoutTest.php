<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Models\User;

class LogoutTest extends TestCase
{
    protected $user;

    public function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
    }

    /** @test */
    public function it_returns_authenticated_with_successfully_message_on_user_logout()
    {
        $token = $this->postJson('api/login', [
            'email' => $this->user->email,
            'password' => 'secret',
        ])->assertStatus(200)->decodeResponseJson()['token'];

        $response = $this->actingAs($this->user, 'api')->postJson('api/logout', [], [
            'Authorization' => 'Bearer'.$token,
        ]);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Successfully logged out']);

        $this->assertGuest('api');
    }

    /** @test */
    public function it_throws_failed_to_validate_token_error_on_user_logout()
    {
        $token = 'wrong_token';

        $response = $this->postJson('api/logout', [], [
            'Authorization' => 'Bearer'.$token,
        ]);

        $response->assertStatus(401)
            ->assertJson(['message' => 'Wrong number of segments']);
    }
}
