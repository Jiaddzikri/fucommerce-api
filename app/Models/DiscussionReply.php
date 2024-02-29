<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscussionReply extends Model
{
    use HasFactory;

    protected $table = "discussion_replies";
    protected $primaryKey = "id";
    public $incrementing = false;
    protected $keyType = "string";
}
