<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vendor extends Model
{
    /** @use HasFactory<\Database\Factories\VendorFactory> */
    use HasFactory;

    protected $table = 'vendors';

    public $incrementing = true;
    public $timestamps = true;

    public $fillable = [
        'name'
    ];

    public function products(): HasMany {
        return $this->hasMany(Product::class);
    }
}
