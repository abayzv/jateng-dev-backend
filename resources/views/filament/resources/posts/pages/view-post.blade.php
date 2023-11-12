<x-filament-panels::page>
    @if ($this->hasInfolist())
        {{ $this->infolist }}
    @else
        {{ $this->form }}
    @endif

    @livewire('list-comments', ['post' => $this->data])
</x-filament-panels::page>
