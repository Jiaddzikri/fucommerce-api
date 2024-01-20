<?php

namespace App\Response;

class FindSessionResponse
{
    public ?string $user_id = null;
    public ?string $session_id = null;
    public ?string $username = null;
    public ?string $role = null;
    public ?string $accessToken = null;
    public ?string $expires = null;
}
