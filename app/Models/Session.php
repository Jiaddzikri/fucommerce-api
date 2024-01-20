<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Session extends Model
{
    protected $primaryKey = "id";
    public $incrementing = false;
    protected $table = "sessions";
    public $fillable = [
        "id",
        "user_id",
        "session_key",
        "access_token",
        "expires"
    ];
    public $timestamp = true;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, "foreign_key", "user_id");
    }
}
