<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductDiscussion extends Model
{
    protected $table = "product_discussion";
    protected $primaryKey = "id";
    public $incrementing = false;
    protected $keyType = "string";
}
