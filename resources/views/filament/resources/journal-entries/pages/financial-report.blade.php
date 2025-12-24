<x-filament-panels::page>
    {{ $this->form }}

    <div class="flex justify-end">
        <x-filament::button wire:click="calculateReport">
            Terapkan Filter
        </x-filament::button>
    </div>

    {{ $this->reportInfolist }}
</x-filament-panels::page>