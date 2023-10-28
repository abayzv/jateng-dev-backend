<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Brand;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-archive-box';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Details')
                    ->description('Add product details')
                    ->aside()
                    ->collapsible()
                    ->schema([
                        TextInput::make('name')
                            ->autofocus()
                            ->required()
                            ->unique()
                            ->placeholder(__('Name'))
                            ->maxLength(255),

                        RichEditor::make('description')
                            ->required()
                            ->maxLength(255)
                            ->placeholder(__('Description')),
                    ]),

                Section::make('Information')
                    ->description('Add product information')
                    ->aside()
                    ->collapsible()
                    ->schema([
                        TextInput::make('price')
                            ->required()
                            ->maxLength(255)
                            ->placeholder(__('Price')),

                        Select::make('category_id')
                            ->relationship('category', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->placeholder(__('Select Category')),

                        Select::make('brand_id')
                            ->relationship('brand', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->placeholder(__('Select Brand')),

                        Select::make('user_id')
                            ->relationship('author', 'name')
                            ->placeholder(__('Select Author')),
                    ]),

                Section::make('Galleries')
                    ->description('Add product galleries')
                    ->aside()
                    ->collapsible()
                    ->schema([
                        FileUpload::make('images')
                            ->panelLayout('grid')
                            ->imagePreviewHeight('150')
                            ->multiple()
                            ->image()
                            ->imageEditor()
                            ->imageEditorAspectRatios([
                                null,
                                '16:9',
                                '4:3',
                                '1:1',
                            ]),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                ImageColumn::make('brand.image')
                    ->circular()
                    ->extraImgAttributes(fn (Product $record): array => [
                        'title' => $record->brand->name,
                    ]),
                TextColumn::make('category.name')
                    ->sortable(),
                TextColumn::make('price')
                    ->prefix('Rp')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('author.name')
                    ->sortable()
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
