<?php

namespace App\Livewire;

use App\Filament\Resources\AddressResource;
use App\Filament\Resources\CustomerResource;
use App\Models\Address;
use Faker\Provider\ar_EG\Text;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ViewField;
use Filament\Tables\Actions\DeleteAction;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
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
    public $address;

    public function table(Table $table): Table
    {
        $query = Address::query()->where('customer_id', $this->customer['id']);
        $this->address = $query->first()->toArray();
        $distance = $query->first()->calculateDistance();

        return $table
            ->query($query)
            ->columns([
                TextColumn::make('address_name'),
                TextColumn::make('recipient_name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('recipient_phone_number'),
                TextColumn::make('address_distance')
                    ->state(fn ($record) => $record->calculateDistance() . ' km'),
            ])
            ->filters([
                // ...
            ])
            ->actions([
                ViewAction::make()
                    ->form([
                        TextInput::make('address_name'),
                        Grid::make()
                            ->schema([
                                TextInput::make('recipient_name'),
                                TextInput::make('recipient_phone_number'),
                            ]),
                        ViewField::make('address_map')
                            ->view('livewire.address-map', ['data' => $this->customer['id']]),
                        // ViewField::make('address_distance')
                        //     ->view('livewire.address-distance'),
                    ]),
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
