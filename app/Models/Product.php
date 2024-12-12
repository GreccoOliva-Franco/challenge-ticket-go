<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory;

    protected $table = 'products';
    protected $with = [
        'ratings',
        'vendor'
    ];

    public $incrementing = true;
    public $timestamps = true;

    public $fillable = [
        'name',
        'vendor_id',
    ];

    public function vendor(): BelongsTo {
        return $this->belongsTo(Vendor::class);
    }

    public function ratings(): HasMany {
        return $this->hasMany(Rating::class);
    }
}
