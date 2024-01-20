<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductImages extends Model
{
    protected $table = "product_images";
    protected $primaryKey = "id";
    public $fillable = ["product_id", "main_image_name", "main_image_path", "second_image_name", "second_image_path", "third_image_name", "third_image_path", "fourth_image_name", "fourth_image_path"];
    public $timestamps = false;


    public function product(): BelongsTo
    {
        return $this->belongsTo(Products::class, "foreign_key", "product_id");
    }
}
