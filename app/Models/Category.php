<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Category extends Model
{
    use HasFactory;

    protected $primaryKey = "id";
    protected $table = "categories";
    public $fillable = ["id", "name"];
    protected $keyType = "string";
    public $incrementing = false;
    public $timestamp = true;
}
