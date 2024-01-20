<?php

namespace App\Requests;

class CreateSessionRequest
{
    public ?string $role = null;
    public ?string $user_id = null;
}
