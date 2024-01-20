<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCategory3 extends Model
{
    protected $primaryKey = "id";
    protected $table = "sub_categories_3";
    public $fillable = ["id", "sub_category_2_id", "name"];
    protected $keyType = "string";

    public $timestamp = true;
}
