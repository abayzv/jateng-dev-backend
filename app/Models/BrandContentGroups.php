<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class BrandContentGroups extends Model
{
    use HasFactory;

    protected $fillable = [
        'brand_content_id',
        'content_id',
    ];

    public function brandContent()
    {
        return $this->belongsTo(BrandContent::class);
    }

    public function content()
    {
        return $this->belongsTo(Content::class);
    }
}
