<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    use HasFactory;

    protected $fillable = [
        'content_id',
        'campaign_name',
        'campaign_slug',
        'url',
        'location',
        'start_date',
        'end_date',
    ];

    public function stats()
    {
        return $this->hasMany(CampaignStats::class);
    }

    public function content()
    {
        return $this->belongsTo(Content::class);
    }

    public function platforms()
    {
        return $this->belongsToMany(CampaignPlatform::class, 'campaign_stats');
    }
}
