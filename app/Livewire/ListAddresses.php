<?php

namespace App\Livewire;

use App\Models\Address;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\DeleteAction;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class ListAddresses extends Component implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;
    public $customer;

    public function table(Table $table): Table
    {
        $query = Address::query()->where('customer_id', $this->customer['id']);
        return $table
            ->query($query)
            ->columns([
                TextColumn::make('address_name'),
                TextColumn::make('recipient_name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('recipient_phone_number')
            ])
            ->filters([
                // ...
            ])
            ->actions([
                ViewAction::make()
                    // ->url(fn (Address $address) => route('address.show', ['address' => $address]))
                    ->name('View'),
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

    public function render(): View
    {
        return view('livewire.list-addresses');
    }
}
