<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReplySupport;
use App\Http\Resources\ReplySupportResource;
use App\Repositories\ReplySupportRepository;

class ReplySupportController extends Controller
{
    protected $repository;

    public function __construct(ReplySupportRepository $replySupportRepository)
    {
        $this->repository = $replySupportRepository;
    }

    public function createReply(StoreReplySupport $request)
    {
        $reply = $this->repository->createReplyToSupportId($request->validated());

        return new ReplySupportResource($reply);
    }
}
