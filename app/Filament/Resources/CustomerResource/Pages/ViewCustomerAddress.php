<?php

namespace App\Filament\Resources\CustomerResource\Pages;

use App\Filament\Resources\CustomerResource;
use App\Models\Address;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewCustomerAddress extends ViewRecord
{
    protected static string $resource = CustomerResource::class;
    protected static ?string $title = 'View Customer Address';
    protected static string $view = 'filament.resources.customers.pages.view-customer-address';

    public $address_id;

    // public function mount(int|string $record): void
    // {
    //     $this->address = Address::where('id', $this->address_id)->first()->toArray();
    // }
    protected function mutateFormDataBeforeFill(array $data): array
    {
        $address = Address::where('id', $this->address_id)->first()->toArray();
        return $address;
    }
}
