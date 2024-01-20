<?php

namespace App\Requests;

class LoginRequest
{
    public ?string $email = null;
    public ?string $password = null;
}