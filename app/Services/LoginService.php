<?php

namespace App\Services;

use App\Models\User;
use App\Requests\LoginRequest;
use App\Response\LoginResponse;

interface LoginService
{
    public function __construct(SessionServiceImplementation $session, User $userModel);
    public function login(LoginRequest $request): LoginResponse;
}
