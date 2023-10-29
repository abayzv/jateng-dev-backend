<?php

namespace App\Filament\Resources\YesResource\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\CampaignStats;
use App\Models\CampaignPlatform;

class CampaignClickChart extends ChartWidget
{
    protected static ?string $heading = 'Campaign analytics by platform';

    protected function getData(): array
    {

        $platforms = CampaignPlatform::all()->pluck('platform')->toArray();
        $dataClicks = [];
        $dataImpressions = [];
        $dataSales = [];

        // each platform, create label and data
        foreach ($platforms as $platform) {
            $clicks = CampaignStats::whereHas('campaignPlatform', function ($query) use ($platform) {
                $query->where('platform', $platform);
            })->sum('click');

            $impressions = CampaignStats::whereHas('campaignPlatform', function ($query) use ($platform) {
                $query->where('platform', $platform);
            })->sum('impression');

            $sales = CampaignStats::whereHas('campaignPlatform', function ($query) use ($platform) {
                $query->where('platform', $platform);
            })->sum('sales');

            $dataClicks[] = $clicks;
            $dataImpressions[] = $impressions;
            $dataSales[] = $sales;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Campaign Clicks',
                    'data' => $dataClicks,
                    'borderColor' => 'red',
                    'borderRadius' => 10,
                ],
                [
                    'label' => 'Campaign Impressions',
                    'data' => $dataImpressions,
                    'borderRadius' => 10,
                ],
                [
                    'label' => 'Campaign Sales',
                    'data' => $dataSales,
                    'borderColor' => 'orange',
                    'borderRadius' => 10,
                ],
            ],
            'labels' => $platforms,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
