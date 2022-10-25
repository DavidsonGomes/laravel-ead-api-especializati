<?php

namespace App\Repositories;

use App\Models\ReplySupport;
use App\Models\Support;
use App\Models\User;

class ReplySupportRepository
{
    protected $entity;

    public function __construct(Support $model)
    {
        $this->entity = $model;
    }

    public function createReplyToSupportId(String $supportId, array $data)
    {
        $user = $this->getUserAuth();

        return $this->getSupport($supportId)
            ->replies()
            ->create([
                'user_id' => $user->id,
                'description' => $data['description']
            ]);
    }

    public function getSupport(String $id): Support
    {
        return $this->entity->query()->findOrFail($id);
    }

    private function getUserAuth(): User
    {
        // return auth()->user();
        return User::query()->first();
    }
}
