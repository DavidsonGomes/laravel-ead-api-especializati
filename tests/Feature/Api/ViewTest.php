<?php

namespace Tests\Feature\Api;

use App\Models\Lesson;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ViewTest extends TestCase
{
    use UtilsTrait;

    public function test_view_lesson_unauthenticated()
    {
        $response = $this->postJson('/lessons/viewed');

        $response->assertStatus(401);
    }

    public function test_view_lesson_error_validator()
    {
        $response = $this->postJson('/lessons/viewed', [], $this->defaultHeaders());

        $response->assertStatus(422);
    }

    public function test_view_lesson_not_found()
    {
        $payload = [
            'lesson' => 'fake_id'
        ];

        $response = $this->postJson(
            '/lessons/viewed',
            $payload,
            $this->defaultHeaders()
        );

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['lesson']);
    }

    public function test_view_lesson()
    {
        $lesson = Lesson::factory()->create();

        $payload = [
            'lesson' => $lesson->id
        ];

        $response = $this->postJson(
            '/lessons/viewed',
            $payload,
            $this->defaultHeaders()
        );

        $response->assertStatus(200);
    }
}
