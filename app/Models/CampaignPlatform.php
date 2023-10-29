<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CampaignPlatform extends Model
{
    use HasFactory;

    protected $fillable = [
        'platform',
        'logo',
    ];

    public function campaignStats()
    {
        return $this->hasMany(CampaignStats::class);
    }

    public function campaigns()
    {
        return $this->belongsToMany(Campaign::class, 'campaign_stats');
    }
}
