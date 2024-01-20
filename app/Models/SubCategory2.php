<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCategory2 extends Model
{
    protected $primaryKey = "id";
    protected $table = "sub_categories_2";
    public $fillable = ["id", "sub_category_1_id", "name"];
    protected $keyType = "string";

    public $timestamp = true;
}
