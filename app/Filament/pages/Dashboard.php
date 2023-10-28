<?php

namespace App\Filament\Pages;

use App\Filament\Resources\YesResource\Widgets\StatsOverview;

class Dashboard extends \Filament\Pages\Dashboard
{
    public function getWidgets(): array
    {
        return [
            StatsOverview::class,
        ];
    }
}
