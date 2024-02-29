<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Products extends Model
{
    use HasFactory;

    protected $table = "products";
    protected $primaryKey = "id";
    public $fillable = ["id", "category_id", "sub_category_1_id", "sub_category_2_id", "sub_category_3_id", "sub_category_4_id", "user_id", "seller_id", "inventory_id", "discount_id", "name", "slug",  "price", "description"];
    public $timestamp = true;
    public $incrementing = false;
    protected $keyType = "string";

    // public function category(): BelongsTo
    // {
    //     return $this->belongsTo(Category::class, "foreign_key", "id");
    // }

    // public function inventory(): belongsTo
    // {
    //     return $this->belongsTo(Inventory::class, "foreign_key", "id");
    // }

    // public function discount(): BelongsTo
    // {
    //     return $this->belongsTo(Discount::class, "foreign_key", "id");
    // }

    // public function images(): HasMany
    // {
    //     return $this->hasMany(ProductImages::class, "foreign_key", "id");
    // }
}
