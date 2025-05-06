<x-filament-widgets::widget>
    <form wire:submit.prevent="filter">
        {{ $this->form }}
        
        <div class="flex justify-end gap-3 mt-3">
            <x-filament::button type="button" color="danger" wire:click="resetFilter">
                Reset
            </x-filament::button>
            
            <x-filament::button type="submit" color="primary">
                Terapkan Filter
            </x-filament::button>
        </div>
    </form>
</x-filament-widgets::widget>