<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class BrandContent extends Model
{
    use HasFactory;

    protected $fillable = [
        'brand_id',
        'name',
        'type',
        'is_active',
    ];

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function contents()
    {
        return $this->belongsToMany(Content::class, 'brand_content_groups');
    }

    public function content()
    {
        return $this->hasMany(BrandContentGroups::class);
    }
}
