<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Filament\Resources\PostResource\RelationManagers;
use App\Models\Post;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Support\Markdown;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;
    protected static ?string $navigationGroup = 'Blog';

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->columns(2)
                    ->schema([
                        TextInput::make('title')
                            ->columnSpan(2)
                            ->autofocus()
                            ->required()
                            ->placeholder(__('Title'))
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state)))
                            ->maxLength(255),
                        TextInput::make('slug')
                            ->autofocus()
                            ->required()
                            ->placeholder(__('Slug'))
                            ->maxLength(255),
                        Select::make('category_id')
                            ->relationship('category', 'name')
                            ->placeholder(__('Select Category'))
                            ->searchable()
                            ->preload()
                            ->nullable(),
                        TextInput::make('excerpt')
                            ->autofocus()
                            ->columnSpan(2)
                            ->required()
                            ->placeholder(__('Excerpt'))
                            ->maxLength(255),
                        MarkdownEditor::make('content')
                            ->autofocus()
                            ->columnSpan(2)
                            ->required()
                            ->placeholder(__('Content')),
                        FileUpload::make('featured_image')
                            ->image()
                            ->imageEditor()
                            ->columnSpan(2)
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('author.name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->sortable()
                    ->color(fn (Post $record) => match ($record->status) {
                        'draft' => 'gray',
                        'published' => 'success',
                        'archived' => 'red',
                    }),
                TextColumn::make('published_at')
                    ->sortable()
                    ->default('Not Published'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Action::make('publish')
                    ->action(function (Post $record) {
                        if ($record->status === 'published') {
                            $record->update(['status' => 'draft']);
                            Notification::make()
                                ->title('Post Unpublished')
                                ->body('The post has been unpublished.')
                                ->success()
                                ->send();
                        } else {
                            $record->update(['status' => 'published', 'published_at' => now()]);
                            Notification::make()
                                ->title('Post Published')
                                ->body('The post has been published.')
                                ->success()
                                ->send();
                        }
                    })
                    ->label(fn (Post $record) => match ($record->status) {
                        'draft' => 'Publish',
                        'published' => 'Unpublish',
                        'archived' => 'Publish',
                    })
                    ->icon(fn (Post $record) => match ($record->status) {
                        'draft' => 'heroicon-o-check-circle',
                        'published' => 'heroicon-o-x-circle',
                        'archived' => 'heroicon-o-check-circle',
                    })
                    ->color(fn (Post $record) => match ($record->status) {
                        'draft' => 'success',
                        'published' => 'danger',
                        'archived' => 'success',
                    }),
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'view' => Pages\ViewPost::route('/{record}'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
