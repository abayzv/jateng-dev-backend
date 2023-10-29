<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CampaignPlatformResource\Pages;
use App\Filament\Resources\CampaignPlatformResource\RelationManagers;
use App\Models\CampaignPlatform;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CampaignPlatformResource extends Resource
{
    protected static ?string $model = CampaignPlatform::class;

    protected static ?string $navigationGroup = 'Campaigns';

    protected static ?string $navigationIcon = 'heroicon-o-computer-desktop';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Campaign Platform')
                    ->description('Add campaign platform')
                    ->aside()
                    ->schema([
                        TextInput::make('platform')
                            ->autofocus()
                            ->required()
                            ->unique()
                            ->placeholder(__('Platform name, e.g. Facebook, Instagram, etc.'))
                            ->maxLength(255),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('platform')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('campaigns_count')->counts('campaigns'),
                TextColumn::make('created_at'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCampaignPlatforms::route('/'),
            'create' => Pages\CreateCampaignPlatform::route('/create'),
            'edit' => Pages\EditCampaignPlatform::route('/{record}/edit'),
        ];
    }
}
