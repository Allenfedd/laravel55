<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    /** @test */
    public function it_returns_token_and_expires_in()
    {
        $user = factory(User::class)->make();

        $response = $this->postJson('api/register', [
            'name' => $user->name,
            'email' => $user->email,
            'password' => 'secret'
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['token', 'expires_in']);
    }

    /** @test */
    public function it_throws_field_required_validation_errors()
    {
        $response = $this->postJson('api/register', []);

        $response->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'name' => ['The name field is required.'],
                    'email' => ['The email field is required.'],
                    'password' => ['The password field is required.']
                ]
            ]);
    }

    /** @test */
    public function it_throws_field_validation_errors()
    {
        $response = $this->postJson('api/register', [
            'name' => 'invalid_name 123',
            'email' => 'invalid_email',
            'password' => '123'
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'errors' => [
//                    'name' => ['The name may only contain letters and numbers.'],
                    'email' => ['The email must be a valid email address.'],
                    'password' => ['The password must be at least 6 characters.']
                ]
            ]);

    }

    /** @test */
    public function it_throws_user_name_and_email_taken_errors()
    {
        $user = factory(User::class)->create();

        $response = $this->postJson('api/register', [
            'name' => $user->name,
            'email' => $user->email,
            'password' => 'secret'
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'name' => ['The name has already been taken.'],
                    'email' => ['The email has already been taken.']
                ]
            ]);
    }
}
