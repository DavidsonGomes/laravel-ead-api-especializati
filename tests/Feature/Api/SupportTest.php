<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SupportTest extends TestCase
{
    use UtilsTrait;

    public function test_get_supports_unauthenticated()
    {
        $response = $this->postJson('/supports');

        $response->assertStatus(401);
    }
}
