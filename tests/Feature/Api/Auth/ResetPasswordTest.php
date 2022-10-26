<?php

namespace Tests\Feature\Api\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Tests\Feature\Api\UtilsTrait;
use Tests\TestCase;

class ResetPasswordTest extends TestCase
{
    use UtilsTrait;

    public function test_send_email_password_reset_with_invalid_email()
    {
        $response = $this->postJson('/forgot-password');

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'email',
            ]);
    }

    public function test_send_email_password_reset()
    {
        Notification::fake();

        $user = $this->createUser();
        $token = $this->createUserToken($user);

        Event::fake();

        $response = $this->postJson('/forgot-password', [
            'email' => $user->email,
        ], [
            'Authorization' => "Bearer {$token}",
        ]);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'status' => 'We have emailed your password reset link!'
            ]);
    }

    public function test_password_reset_error_validator()
    {
        $response = $this->postJson('/reset-password');

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'token',
                'email',
                'password',
            ]);
    }

    public function test_password_reset_error_token_invalid()
    {
        $user = $this->createUser();

        $response = $this->postJson('/reset-password', [
            'token' => '',
            'email' => $user->email,
            'password' => 123456,
            'password_confirmation' => 123456,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'token',
            ]);
    }

    public function test_password_reset_error_email_invalid()
    {
        $user = $this->createUser();
        $token = $this->createUserToken($user);

        $response = $this->postJson('/reset-password', [
            'token' => $token,
            'email' => 'invalid_email',
            'password' => 123456,
            'password_confirmation' => 123456,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'email',
            ]);
    }

    public function test_password_reset_error_password_invalid_min_characters()
    {
        $user = $this->createUser();
        $token = $this->createUserToken($user);

        $response = $this->postJson('/reset-password', [
            'token' => $token,
            'email' => $user->email,
            'password' => 1234,
            'password_confirmation' => 1234,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'password',
            ]);
    }

    public function test_password_reset_error_password_invalid_max_characters()
    {
        $user = $this->createUser();
        $token = $this->createUserToken($user);

        $password = Str::random(16);

        $response = $this->postJson('/reset-password', [
            'token' => $token,
            'email' => $user->email,
            'password' => $password,
            'password_confirmation' => $password,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'password',
            ]);
    }

    public function test_password_reset_error_password_confirmation()
    {
        $user = $this->createUser();
        $token = $this->createUserToken($user);

        $response = $this->postJson('/reset-password', [
            'token' => $token,
            'email' => $user->email,
            'password' => 123456,
            'password_confirmation' => 1234567,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'password',
            ]);
    }

    public function test_password_reset()
    {
        $user = $this->createUser();
        $token = $this->createUserToken($user);

        $response = $this->postJson('/reset-password', [
            'token' => $token,
            'email' => $user->email,
            'password' => 123456,
            'password_confirmation' => 123456,
        ]);

        $response->assertStatus(422);
    }

    public function test_the_user_can_update_their_password()
    {
        $user = User::factory()->create([
            'email' => 'user@domain.com',
            'password' => Hash::make('oldpassword')
        ]);

        $token = Password::createToken(User::first());

        $response = $this->postJson('/reset-password', [
            'email' => 'user@domain.com',
            'password' => 'newpassword',
            'password_confirmation' => 'newpassword',
            'token' => $token
        ]);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'status' => 'Your password has been reset!'
            ]);

        dump(User::first()->password);

        $this->assertTrue(Hash::check('newpassword', User::first()->password));
    }
}
