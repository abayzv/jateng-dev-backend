<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BrandContent extends Model
{
    use HasFactory;

    protected $fillable = [
        'brand_id',
        'name',
        'type',
        'is_active',
        'is_have_product',
        'product',
        'is_have_banner',
        'banner',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'meta_image',
    ];
}
