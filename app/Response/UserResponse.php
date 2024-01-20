<?php

namespace App\Response;

class UserResponse
{
    public ?string $id = null;
    public ?string $email = "";
    public ?string $username = null;
    public ?string $role = null;
    public ?string $phoneNumber = null;
    public ?array $address = [];
}