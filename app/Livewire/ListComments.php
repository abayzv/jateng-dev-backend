<?php

namespace App\Livewire;

use App\Models\Comment;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Table;
use Livewire\Component;

class ListComments extends Component implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;
    public $post;

    public function table(Table $table): Table
    {
        $query = Comment::query()->where('commentable_id', $this->post['id']);

        return $table
            ->query($query)
            ->columns([
                TextColumn::make('body'),
                TextColumn::make('author.name'),
                ToggleColumn::make('approved')
            ])
            ->filters([
                // ...
            ])
            ->actions([
                DeleteAction::make(),
            ])
            ->headerActions([
                // CreateAction::make()
                //     ->form([
                //         TextInput::make('address_name')
                //             ->autofocus()
                //             ->required()
                //             ->placeholder(__('Address Name'))
                //             ->maxLength(255),
                //     ])
            ])
            ->bulkActions([
                // ...
            ]);
    }

    public function render()
    {
        return view('livewire.list-comments');
    }
}
