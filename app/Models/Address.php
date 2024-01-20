<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Address extends Model
{
    use HasFactory;

    protected $table = "user_addresses";
    public $fillable = [
       "id", "seller_id", "customer_id", "province", "regency", "district", "description", "postal_code", "created_at", "updated_at"
    ];

    public function provinces(): BelongsTo
    {
        return $this->belongsTo("provinces", "foreign_key", "name");
    }

    public function regencies(): BelongsTo
    {
        return $this->belongsTo("regencies", "foreign_key", "name");
    }

    public function districts(): BelongsTo
    {
        return $this->belongsTo("districts", "foreign_key", "name");
    }
}
