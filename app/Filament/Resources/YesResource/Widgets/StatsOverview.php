<?php

namespace App\Filament\Resources\YesResource\Widgets;

use App\Models\Product;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?string $pollingInterval = '10s';

    protected function getStats(): array
    {
        $product = Product::all()->count();
        $brand = Product::all()->count();

        return [
            Stat::make('Total Products', $product)
                ->icon('heroicon-o-archive-box')
                ->color('danger'),
            Stat::make('Total Brands', $brand),
            Stat::make('Bounce rate', '21%'),
        ];
    }
}
