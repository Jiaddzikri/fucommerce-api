<?php

namespace App\Response;

class LoginResponse
{
    public ?string $userId = null;
    public ?string $role = null;
    public ?string $accessToken = null;
}
