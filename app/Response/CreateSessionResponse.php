<?php

namespace App\Response;

class CreateSessionResponse
{
    public ?string $user_id = null;
    public ?string $role = null;
    public ?string $accessToken = null;
    public ?string $expire = null;
}
