<?php

namespace Tests\Feature\Api;

use App\Models\Support;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Str;

class ReplyTest extends TestCase
{
    use UtilsTrait;

    public function test_reply_support_unauthenticated()
    {
        $response = $this->postJson('/replies');

        $response->assertStatus(401);
    }

    public function test_reply_support_error_validation()
    {
        $response = $this->postJson('/replies', [], $this->defaultHeaders());

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'support',
                'description',
            ]);
    }

    public function test_reply_support_invalid()
    {
        $payloads = [
            'support' => 'fake_id',
            'description' => 'Test'
        ];

        $response = $this->postJson('/replies', $payloads, $this->defaultHeaders());

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'support',
            ]);
    }

    public function test_reply_support_description_null()
    {
        $support = Support::factory()->create();

        $payloads = [
            'support' => $support->id,
            'description' => ''
        ];

        $response = $this->postJson('/replies', $payloads, $this->defaultHeaders());

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'description',
            ]);
    }

    public function test_reply_support_description_invalid_min_characters()
    {
        $support = Support::factory()->create();

        $payloads = [
            'support' => $support->id,
            'description' => Str::random(1)
        ];

        $response = $this->postJson('/replies', $payloads, $this->defaultHeaders());

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'description',
            ]);
    }

    public function test_reply_support_description_invalid_max_characters()
    {
        $support = Support::factory()->create();

        $payloads = [
            'support' => $support->id,
            'description' => Str::random(10001)
        ];

        $response = $this->postJson('/replies', $payloads, $this->defaultHeaders());

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'description',
            ]);
    }

    public function test_reply_support()
    {
        $support = Support::factory()->create();

        $payloads = [
            'support' => $support->id,
            'description' => 'Test'
        ];

        $response = $this->postJson('/replies', $payloads, $this->defaultHeaders());

        $response->assertStatus(201);
    }
}
