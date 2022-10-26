<?php

namespace Tests\Feature\Api;

use App\Models\User;

trait UtilsTrait
{
    public function defaultHeaders()
    {
        $token = $this->createTokenUser();

        return [
            'Authorization' => "Bearer {$token}",
        ];
    }

    private function createTokenUser()
    {
        $user = User::factory()->create();
        $token = $user->createToken('test')->plainTextToken;

        return $token;
    }
}
