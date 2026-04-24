<x-filament-panels::page>
    <form wire:submit.prevent="submit">
        {{ $this->form }}

        <div class="mt-4">
            <x-filament::button type="submit" size="lg">
                
            </x-filament::button>
        </div>
    </form>
</x-filament-panels::page>