<?php

namespace Tests\Feature\Api;

use App\Models\Lesson;
use App\Models\Module;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LessonTest extends TestCase
{
    use UtilsTrait;

    public function test_get_lessons_unauthenticated()
    {
        $response = $this->getJson('/modules/fake_id/lessons');

        $response->assertStatus(401);
    }

    public function test_get_lessons_module_not_found()
    {
        $response = $this->getJson('/modules/fake_id/lessons', $this->defaultHeaders());

        $response->assertStatus(200)
            ->assertJsonCount(0, 'data');
    }

    public function test_get_lessons_module()
    {
        $module = Module::factory()->create();

        $response = $this->getJson("/modules/{$module->id}/lessons", $this->defaultHeaders());

        $response->assertStatus(200);
    }

    public function test_create_lessons_module_total()
    {
        $module = Module::factory()->create();
        Lesson::factory()->count(10)->create([
            'module_id' => $module->id
        ]);

        $response = $this->getJson("/modules/{$module->id}/lessons", $this->defaultHeaders());

        $response->assertStatus(200)
        ->assertJsonCount(10, 'data');
    }

    public function test_get_lesson_unauthenticated()
    {
        $response = $this->getJson('/lessons/fake_id');

        $response->assertStatus(401);
    }

    public function test_get_lesson_not_found()
    {
        $response = $this->getJson('/lessons/fake_id', $this->defaultHeaders());

        $response->assertStatus(404);
    }

    public function test_get_lesson()
    {
        $lesson = Lesson::factory()->create();

        $response = $this->getJson("/lessons/{$lesson->id}", $this->defaultHeaders());

        $response->assertStatus(200);
    }
}
