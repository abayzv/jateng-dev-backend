<?php

namespace App\Filament\Resources\CampaignPlatformResource\Pages;

use App\Filament\Resources\CampaignPlatformResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCampaignPlatform extends EditRecord
{
    protected static string $resource = CampaignPlatformResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
