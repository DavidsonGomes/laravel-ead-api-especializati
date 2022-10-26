<?php

namespace App\Repositories;

use App\Models\ReplySupport;
use App\Repositories\Traits\RepositoryTrait;

class ReplySupportRepository
{
    use RepositoryTrait;

    protected $entity;

    public function __construct(ReplySupport $model)
    {
        $this->entity = $model;
    }

    public function createReplyToSupportId(array $data)
    {
        $user = $this->getUserAuth();

        return $this->entity->query()->create([
            'support_id' => $data['support'],
            'user_id' => $user->id,
            'description' => $data['description']
        ]);
    }
}
