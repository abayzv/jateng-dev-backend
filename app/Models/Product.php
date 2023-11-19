<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Product extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory;

    protected $fillable = ['name', 'price', 'description', 'images', 'category_id', 'user_id', 'brand_id'];
    protected $casts = [
        'images' => 'array',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function link()
    {
        return $this->hasOne(LinkProduct::class);
    }

    public function identifier()
    {
        return $this->hasOne(ProductIdentifier::class);
    }

    public function inventory()
    {
        return $this->hasOne(ProductInventory::class);
    }
}
