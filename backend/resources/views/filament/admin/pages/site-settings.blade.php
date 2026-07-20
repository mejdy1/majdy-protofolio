<x-filament-panels::page>
    <form wire:submit="save">
        {{ $this->form }}

        <div class="mt-6 flex items-center justify-end gap-3">
            <x-filament::button type="submit" icon="heroicon-o-check">
                Save settings
            </x-filament::button>
        </div>
    </form>
</x-filament-panels::page>
