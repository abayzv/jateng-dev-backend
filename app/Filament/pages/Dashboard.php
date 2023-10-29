<?php

namespace App\Filament\Pages;

use App\Filament\Resources\YesResource\Widgets\StatsOverview;
use App\Filament\Resources\YesResource\Widgets\CampaignClickChart;

class Dashboard extends \Filament\Pages\Dashboard
{
    public function getWidgets(): array
    {
        return [
            StatsOverview::class,
            CampaignClickChart::class,
        ];
    }
}
