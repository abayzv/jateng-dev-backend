<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContentProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'brand_content_id',
        'product_id',
    ];
}
