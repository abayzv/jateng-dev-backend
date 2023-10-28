<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BrandResource\Pages;
use App\Filament\Resources\BrandResource\RelationManagers;
use App\Models\Brand;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BrandResource extends Resource
{
    protected static ?string $model = Brand::class;

    protected static ?string $navigationGroup = 'Brands';
    protected static ?int $navigationSort = 1;

    protected static ?string $navigationIcon = 'heroicon-o-building-office';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Brand Information')
                    ->description('Add brand information')
                    ->aside()
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->placeholder(__('Name'))
                            ->maxLength(255),

                        FileUpload::make('image')
                            ->image()
                            ->imageEditor()
                            ->imageEditorAspectRatios([
                                null,
                                '16:9',
                                '4:3',
                                '1:1',
                            ]),
                    ]),

                Section::make('Content')
                    ->description('Add content')
                    ->aside()
                    ->visibleOn("edit")
                    ->schema([
                        Repeater::make('contentGroups')
                            ->relationship('contentGroups')
                            ->schema([
                                Toggle::make('is_active')
                                    ->label(__('Is Active')),

                                TextInput::make('name')
                                    ->required()
                                    ->placeholder(__('Name'))
                                    ->maxLength(255),

                                Select::make('type')
                                    ->options([
                                        'category' => 'Category',
                                        'slider' => 'Slider',
                                    ])
                                    ->placeholder(__('Type'))
                                    ->required(),

                                Repeater::make('content')
                                    ->relationship('content')
                                    ->simple(
                                        Select::make('content_id')
                                            ->relationship('content', 'name')
                                            ->placeholder(__('Select Content'))
                                            ->required(),
                                    )
                            ])
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')->square(),
                TextColumn::make('name')
                    ->label(__('Name'))
                    ->searchable()
                    ->sortable(),
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
            'index' => Pages\ListBrands::route('/'),
            'create' => Pages\CreateBrand::route('/create'),
            'edit' => Pages\EditBrand::route('/{record}/edit'),
        ];
    }
}
