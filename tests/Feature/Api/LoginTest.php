<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Tests\TestCase;

class LoginTest extends TestCase
{
    protected $user;

    public function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
    }

    /** @test */
    public function it_returns_valid_token_and_expires_in()
    {
        $response = $this->postJson('api/login', [
            'email' => $this->user->email,
            'password' => 'secret'
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['token', 'expires_in']);
        $this->assertAuthenticatedAs($this->user, 'api');
    }

    /** @test */
    public function it_throws_field_required_validation_errors()
    {
        $response = $this->postJson('api/login', []);

        $response->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'email' => ['The email field is required.'],
                    'password' => ['The password field is required.']
                ]
            ]);
    }

    /** @test */
    public function it_throws_invalid_user_errors()
    {
        $response = $this->postJson('api/login', [
            'email' => $this->user->email,
            'password' => 'wrong_password'
        ]);
        $response->assertJson(['message' => 'Invalid credentials']);
    }


    /** @test */
    public function it_returns_the_current_user_when_logged_in()
    {
        $token = $this->postJson('api/login', [
            'email' => $this->user->email,
            'password' => 'secret'
        ])->assertStatus(200)
            ->decodeResponseJson()['token'];

        $response = $this->getJson('api/me', [
            'Authorization' => 'Bearer' . $token
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'user' => [
                    'id' => $this->user->id,
                    'name' => $this->user->name,
                    'email' => $this->user->email
                ]
            ]);
    }

    /** @test */
    public function it_throws_failed_to_validate_token_error_when_using_wrong_token()
    {
        $token = 'wrong_token';

        $response = $this->getJson('api/me', [
            'Authorization' => 'Bearer' . $token
        ]);
        $response->assertStatus(401)
            ->assertJson(['message' => 'Wrong number of segments']);
    }

    /** @test */
    public function it_throws_unauthorized_error_when_current_user_not_logged_in()
    {
        $response = $this->getJson('api/me');

        $response->assertStatus(401)
            ->assertJson(['message' => 'Token not provided']);
    }

}
