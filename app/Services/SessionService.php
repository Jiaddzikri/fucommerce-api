<?php

namespace App\Services;

use App\Models\Session;
use App\Models\User;
use App\Requests\CreateSessionRequest;
use App\Response\CreateSessionResponse;
use App\Response\FindSessionResponse;


interface SessionService
{
    public function __construct(Session $sessionModel, User $userModel);
    public function create(CreateSessionRequest $session): CreateSessionResponse;
    public function find(?string $accessToken = null): FindSessionResponse;
    public function delete(?string $accessToken = null): bool;
}
