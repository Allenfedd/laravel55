<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Notifications\ResetPassword;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ResetPasswordTest extends TestCase
{
    protected $user;

    public function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
    }

    /** @test */
    public function it_sends_a_password_reset_link_email_when_user_requests()
    {
        Notification::fake();

        $response = $this->postJson('api/password/email', ['email' => $this->user->email]);

        $response->assertStatus(200);

        $token = DB::table('password_resets')->where('email', $this->user->email)->first()->token;

        Notification::assertSentTo($this->user, ResetPassword::class, function ($notification) use ($token) {
            return Hash::check($notification->token, $token);
        });
    }

    /** @test */
    public function it_throws_validation_error_on_password_reset()
    {
        Notification::fake();

        $response = $this->postJson('api/password/email', [
            'email' => 'wrong@gmail.com'
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('email');

        Notification::assertNothingSent();
    }

    /** @test */
    public function it_can_reset_users_password()
    {
        $token = app('auth.password.broker')->createToken($this->user);

        $response = $this->postJson('api/password/reset', [
            'token' => $token,
            'email' => $this->user->email,
            'password' => 'new_password',
            'password_confirmation' => 'new_password'
        ]);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Your password has been reset!']);

        $this->assertTrue(Hash::check('new_password', $this->user->fresh()->password));
    }

    /** @test */
    public function it_throws_validation_error_when_given_invalid_reset_token()
    {
        $response = $this->postJson('api/password/reset', [
            'token' => 'wrong_token',
            'email' => $this->user->email,
            'password' => 'new_password',
            'password_confirmation' => 'new_password'
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('email');

        $this->assertFalse(Hash::check('new_password', $this->user->fresh()->password));
    }
}
