<?php

namespace App\Livewire;

use Livewire\Component;

class AddressMap extends Component
{
    public $data;
    public function render()
    {
        return view('livewire.address-map');
    }
}
