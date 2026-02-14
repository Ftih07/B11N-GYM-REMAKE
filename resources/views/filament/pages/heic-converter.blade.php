<x-filament-panels::page>
    <form wire:submit="convert">
        {{ $this->form }}

        <div class="mt-4 flex justify-end">
            <x-filament::button type="submit" wire:loading.attr="disabled">
                <span wire:loading.remove>Convert & Download</span>
                <span wire:loading>Processing...</span>
            </x-filament::button>
        </div>
    </form>
</x-filament-panels::page>