<?php

namespace App\Services;

use App\Models\User;
use App\Requests\RegisterRequest;
use App\Response\RegisterResponse;

interface RegisterService
{
    public function __construct(SessionServiceImplementation $session, User $userModel);
    public function register(RegisterRequest $request): RegisterResponse;
}
