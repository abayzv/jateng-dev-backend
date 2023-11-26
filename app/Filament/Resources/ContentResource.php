<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContentResource\Pages;
use App\Filament\Resources\ContentResource\RelationManagers;
use App\Models\Content;
use Faker\Provider\ar_EG\Text;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class ContentResource extends Resource
{
    protected static ?string $model = Content::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Content Information')
                    ->description('Add content information')
                    ->aside()
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->placeholder(__('Name'))
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state)))
                            ->maxLength(255),

                        TextInput::make('slug')
                            ->required()
                            ->placeholder(__('Slug'))
                            ->maxLength(255),

                        MarkdownEditor::make('description')
                            ->required()
                            ->maxLength(255)
                            ->placeholder(__('Description')),

                        FileUpload::make('cover')
                            ->image()
                            ->imageEditor()
                            ->imageEditorAspectRatios([
                                null,
                                '16:9',
                                '4:3',
                                '1:1',
                            ]),
                        FileUpload::make('thumbnail')
                            ->image()
                            ->imageEditor()
                            ->imageEditorAspectRatios([
                                null,
                                '16:9',
                                '4:3',
                                '1:1',
                            ]),
                    ]),

                Section::make('Meta Data')
                    ->description('This information will be used for SEO purposes')
                    ->aside()
                    ->schema([
                        TextInput::make('meta_title')
                            ->required()
                            ->placeholder(__('Meta Title'))
                            ->maxLength(255),

                        TextInput::make('meta_description')
                            ->required()
                            ->placeholder(__('Meta Description'))
                            ->maxLength(255),

                        TextInput::make('meta_keywords')
                            ->required()
                            ->placeholder(__('Meta Keywords'))
                            ->maxLength(255),

                        FileUpload::make('meta_image')
                            ->image()
                            ->imageEditor()
                            ->imageEditorAspectRatios([
                                null,
                                '16:9',
                                '4:3',
                                '1:1',
                                '19.1:10',
                            ]),
                    ]),

                Section::make('Products')
                    ->description('Add products')
                    ->aside()
                    ->schema([
                        Repeater::make('contentProducts')
                            ->relationship('contentProducts')
                            ->simple(
                                Select::make('product_id')
                                    ->relationship('product', 'name')
                                    ->placeholder(__('Select Product'))
                                    ->required(),
                            )
                    ])
                    ->visibleOn("edit")
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('cover')
                    ->square(),

                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('created_at')
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
            'index' => Pages\ListContents::route('/'),
            'create' => Pages\CreateContent::route('/create'),
            'edit' => Pages\EditContent::route('/{record}/edit'),
        ];
    }
}
