<?php

namespace Tests\Feature\Api;

use App\Models\User;

trait UtilsTrait
{
    public function createUser()
    {
        $user = User::factory()->create();

        return $user;
    }

    public function createUserToken(User $user)
    {
        $token = $user->createToken('test')->plainTextToken;

        return $token;
    }

    public function createTokenUser()
    {
        $user = $this->createUser();
        $token = $this->createUserToken($user);

        return $token;
    }

    public function defaultHeaders()
    {
        $token = $this->createTokenUser();

        return [
            'Authorization' => "Bearer {$token}",
        ];
    }
}
