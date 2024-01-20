<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Model
{
    const CREATED_AT = "created_at";
    const UPDATED_AT = "updated_at";
    protected $table = "users";
    protected $primaryKey = "id";
    public $incrementing = false;
    public $timestamps = true;
    public $keyType = "string";

    public $fillable = [
        "id",
        "username",
        "email",
        "password_hash",
        "role",
        "store_name",
        "store_domain"
    ];
}
