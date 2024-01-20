<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubCategory1 extends Model
{
    protected $primaryKey = "id";
    protected $table = "sub_categories_1";
    public $fillable = ["id", "category_id", "name"];
    protected $keyType = "string";

    public $timestamp = true;
}
