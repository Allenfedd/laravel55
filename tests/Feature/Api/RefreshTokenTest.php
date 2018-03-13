<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Models\User;

class RefreshTokenTest extends TestCase
{
    /** @test */
    public function it_returns_valid_token_and_expires_in_on_user_refresh_token()
    {
        $user = factory(User::class)->create();

        $token = $this->postJson('api/login', [
            'email' => $user->email,
            'password' => 'secret',
        ])->assertStatus(200)
            ->decodeResponseJson()['token'];

        $response = $this->postJson('api/refresh', [], [
            'Authorization' => 'Bearer ' . $token,
        ]);
        $response->assertStatus(200)
            ->assertJsonStructure(['token', 'expires_in']);
    }

    /** @test */
    public function it_throws_failed_to_parsed_token_when_given_wrong_token()
    {
        $token = 'wrong_token';

        $response = $this->postJson('api/refresh', [], [
            'Authorization' => 'Bearer ' . $token,
        ]);

        $response->assertStatus(500)
            ->assertJson(['message' => 'Token could not be parsed from the request.']);
    }
}
