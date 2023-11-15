<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CampaignResource\Pages;
use App\Filament\Resources\CampaignResource\RelationManagers;
use App\Models\Campaign;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class CampaignResource extends Resource
{
    protected static ?string $model = Campaign::class;

    protected static ?string $navigationGroup = 'Campaigns';

    protected static ?string $navigationIcon = 'heroicon-o-megaphone';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Campaign')
                    ->description('Add campaign')
                    ->aside()
                    ->schema([
                        TextInput::make('campaign_name')
                            ->autofocus()
                            ->live(onBlur: true)
                            ->required()
                            ->placeholder(__('Campaign name'))
                            ->afterStateUpdated(fn (Set $set, ?string $state) => $set('campaign_slug', Str::slug($state)))
                            ->maxLength(255),

                        TextInput::make('campaign_slug')
                            ->required()
                            ->placeholder(__('Campaign slug'))
                            ->maxLength(255),

                        TextInput::make('url')
                            ->required()
                            ->placeholder(__('Campaign URL'))
                            ->maxLength(255),

                    ]),

                Section::make('Campaign Location')
                    ->description('Add campaign location')
                    ->aside()
                    ->schema([
                        Select::make('location')
                            ->options([
                                "jakarta" => "Jakarta",
                                "bandung" => "Bandung",
                                "surabaya" => "Surabaya",
                                "yogyakarta" => "Yogyakarta",
                                "bali" => "Bali",
                            ])
                            ->required()
                            ->placeholder(__('Select Location')),
                    ]),


                Section::make('Campaign Time')
                    ->description('Add campaign time')
                    ->aside()
                    ->schema([
                        DatePicker::make('start_date')
                            ->placeholder(__('Start Date')),

                        DatePicker::make('end_date')
                            ->placeholder(__('End Date')),
                    ]),

                Section::make('Campaign Platform')
                    ->description('Add campaign platform')
                    ->aside()
                    ->schema([
                        Repeater::make('stats')
                            ->relationship('stats')
                            ->label(__('Campaign Platform'))
                            ->simple(
                                Select::make('campaign_platform_id')
                                    ->relationship('campaignPlatform', 'platform')
                                    ->placeholder(__('Select Platform')),
                            )
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('campaign_name')
                    ->searchable()
                    ->sortable(),

                ImageColumn::make('platforms.logo')->circular()->stacked(),


                TextColumn::make('location')
                    ->badge()
                    ->searchable()
                    ->sortable(),

                TextColumn::make('start_date')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('end_date')
                    ->searchable()
                    ->sortable(),

                // TextColumn::make('platforms_count')->counts('platforms')
                //     ->searchable()
                //     ->sortable(),

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
            'index' => Pages\ListCampaigns::route('/'),
            'create' => Pages\CreateCampaign::route('/create'),
            'edit' => Pages\EditCampaign::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
