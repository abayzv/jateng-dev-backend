<?php

namespace App\Filament\Resources\CampaignPlatformResource\Pages;

use App\Filament\Resources\CampaignPlatformResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCampaignPlatform extends CreateRecord
{
    protected static string $resource = CampaignPlatformResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
