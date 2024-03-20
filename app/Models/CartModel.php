<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartModel extends Model
{
    protected $primaryKey = "id";
    protected $table = "carts";
    protected $keyType = "string";
    public $incrementing = false;
    public $timestamp = true;
}
