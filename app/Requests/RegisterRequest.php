<?php

namespace App\Requests;

class RegisterRequest
{
    public ?string $username = null;
    public ?string $email = null;
    public ?string $password = null;
}
