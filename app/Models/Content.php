<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'cover',
        'thumbnail',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'meta_image',
    ];

    public function brandContents()
    {
        return $this->belongsToMany(BrandContent::class, 'brand_content_groups');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'content_products');
    }

    public function contentProducts()
    {
        return $this->hasMany(ContentProduct::class);
    }
}
