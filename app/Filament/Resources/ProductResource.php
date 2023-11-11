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
use Filament\Forms\Components\MarkdownEditor;
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

    protected static ?string $navigationGroup = 'Shop';
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
                            ->placeholder(__('Name'))
                            ->maxLength(255),

                        MarkdownEditor::make('description')
                            ->required()
                            ->maxLength(255)
                            ->placeholder(__('Description')),
                    ]),

                Section::make('Product Identifier')
                    ->description('Add product identifier')
                    ->aside()
                    ->collapsible()
                    ->schema([
                        Fieldset::make('identifier')
                            ->relationship('identifier')
                            ->columns(1)
                            ->schema([
                                TextInput::make('sku')
                                    ->placeholder(__('sku')),
                                TextInput::make('upc')
                                    ->placeholder(__('upc')),
                            ])
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

                Section::make('Inventory')
                    ->description('Manage product inventory')
                    ->aside()
                    ->collapsible()
                    ->schema([
                        Fieldset::make('inventory')
                            ->relationship('inventory')
                            ->columns(1)
                            ->schema([
                                TextInput::make('stock')
                                    ->numeric()
                                    ->placeholder(__('0')),
                                Select::make('availability')
                                    ->options([
                                        'in_stock' => 'In Stock',
                                        'always' => 'Always',
                                    ])
                                    ->placeholder(__('Select Availability')),
                            ])
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
                    ]),

                Section::make('External Links')
                    ->description('Add product external links')
                    ->aside()
                    ->collapsible()
                    ->schema([
                        Fieldset::make('link')
                            ->relationship('link')
                            ->columns(1)
                            ->schema([
                                TextInput::make('tokopedia')
                                    ->placeholder(__('https://')),

                                TextInput::make('shopee')
                                    ->placeholder(__('https://')),

                                TextInput::make('lazada')
                                    ->placeholder(__('https://')),
                            ])
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

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
