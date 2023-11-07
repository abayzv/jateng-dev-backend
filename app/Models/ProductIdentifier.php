<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductIdentifier extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'sku',
        'upc',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
