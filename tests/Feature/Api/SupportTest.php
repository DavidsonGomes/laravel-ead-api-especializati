<?php

namespace Tests\Feature\Api;

use App\Models\Lesson;
use App\Models\Support;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Str;

class SupportTest extends TestCase
{
    use UtilsTrait;

    public function test_get_my_supports_unauthenticated()
    {
        $response = $this->getJson('/my-supports');

        $response->assertStatus(401);
    }

    public function test_get_my_supports()
    {
        $user = $this->createUser();
        $token = $this->createUserToken($user);

        Support::factory()->count(50)->create([
            'user_id' => $user->id
        ]);

        Support::factory()->count(50)->create();

        $response = $this->getJson('/my-supports', [
            'Authorization' => "Bearer {$token}",
        ]);

        $response->assertStatus(200)
            ->assertJsonCount(50, 'data');
    }

    public function test_get_supports_unauthenticated()
    {
        $response = $this->getJson('/supports');

        $response->assertStatus(401);
    }

    public function test_get_supports_no_filters()
    {
        $user = $this->createUser();
        $token = $this->createUserToken($user);

        Support::factory()->count(50)->create([
            'user_id' => $user->id
        ]);

        Support::factory()->count(50)->create();

        $response = $this->getJson('/supports', [
            'Authorization' => "Bearer {$token}",
        ]);

        $response->assertStatus(200)
        ->assertJsonCount(100, 'data');
    }

    public function test_get_supports_lesson()
    {
        $lesson = Lesson::factory()->create();


        Support::factory()->count(50)->create();
        Support::factory()->count(50)->create([
            'lesson_id' => $lesson->id
        ]);

        $payloads = [
            'lesson' => $lesson->id
        ];

        $response = $this->json(
            'GET',
            '/supports',
            $payloads,
            $this->defaultHeaders()
        );

        $response->assertStatus(200)
            ->assertJsonCount(50, 'data');
    }

    public function test_get_supports_filter()
    {
        Support::factory()->count(50)->create();
        $support = Support::factory()->create();

        $payloads = [
            'filter' => Str::substr($support->description, 0, 10)
        ];

        $response = $this->json(
            'GET',
            '/supports',
            $payloads,
            $this->defaultHeaders()
        );

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data');
    }

    public function test_get_supports_status_p()
    {
        Support::factory()->count(50)->create([
            'status' => 'P'
        ]);

        Support::factory()->count(50)->create([
            'status' => 'A'
        ]);

        Support::factory()->count(50)->create([
            'status' => 'C'
        ]);

        $payloads = [
            'status' => 'P'
        ];

        $response = $this->json(
            'GET',
            '/supports',
            $payloads,
            $this->defaultHeaders()
        );

        $response->assertStatus(200)
            ->assertJsonCount(50, 'data');
    }

    public function test_get_supports_status_a()
    {
        Support::factory()->count(50)->create([
            'status' => 'P'
        ]);

        Support::factory()->count(50)->create([
            'status' => 'A'
        ]);

        Support::factory()->count(50)->create([
            'status' => 'C'
        ]);

        $payloads = [
            'status' => 'A'
        ];

        $response = $this->json(
            'GET',
            '/supports',
            $payloads,
            $this->defaultHeaders()
        );

        $response->assertStatus(200)
            ->assertJsonCount(50, 'data');
    }

    public function test_get_supports_status_c()
    {
        Support::factory()->count(50)->create([
            'status' => 'P'
        ]);

        Support::factory()->count(50)->create([
            'status' => 'A'
        ]);

        Support::factory()->count(50)->create([
            'status' => 'C'
        ]);

        $payloads = [
            'status' => 'C'
        ];

        $response = $this->json(
            'GET',
            '/supports',
            $payloads,
            $this->defaultHeaders()
        );

        $response->assertStatus(200)
            ->assertJsonCount(50, 'data');
    }

    public function test_create_support_unauthenticated()
    {
        $response = $this->postJson('/supports');

        $response->assertStatus(401);
    }

    public function test_create_supports_error_validation()
    {
        $response = $this->postJson('/supports', [], $this->defaultHeaders());

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'lesson',
                'description',
                'status'
            ]);
    }

    public function test_create_supports_lesson_not_found()
    {
        $response = $this->postJson('/supports', [
            'lesson' => 'fake_id',
            'description' => 'Test',
            'status' => 'P'
        ], $this->defaultHeaders());

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'lesson'
            ]);
    }

    public function test_create_supports_status_invalid()
    {
        $response = $this->postJson('/supports', [
            'lesson' => 'fake_id',
            'description' => 'Test',
            'status' => 'X'
        ], $this->defaultHeaders());

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'status'
            ]);
    }

    public function test_create_supports_description_invalid()
    {
        $response = $this->postJson('/supports', [
            'lesson' => 'fake_id',
            'description' => '',
            'status' => 'P'
        ], $this->defaultHeaders());

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'description'
            ]);
    }

    public function test_create_supports()
    {
        $lesson = Lesson::factory()->create();

        $response = $this->postJson('/supports', [
            'lesson' => $lesson->id,
            'description' => 'Test',
            'status' => 'P'
        ], $this->defaultHeaders());

        $response->assertStatus(201);
    }
}
