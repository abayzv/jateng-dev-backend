<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CampaignStats extends Model
{
    use HasFactory;

    protected $fillable = [
        'campaign_id',
        'campaign_platform_id',
        'impression',
        'click',
        'sales'
    ];

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public function campaignPlatform()
    {
        return $this->belongsTo(CampaignPlatform::class);
    }
}
