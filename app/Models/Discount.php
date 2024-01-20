<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    use HasFactory;

    protected $table = "product_discount";
    public $fillable = [
        "discount", "active"
    ];
    public $timestamp = true;
}
