<x-filament-panels::page>
    {{-- @if ($this->hasInfolist())
        {{ $this->infolist }}
    @else
        {{ $this->form }}
    @endif --}}

    {{-- @livewire('list-addresses', ['customer' => $this->data]) --}}
    {{ dd($this->data) }}
</x-filament-panels::page>
