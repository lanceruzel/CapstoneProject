<x-modal-card name="addStockFormModal" width="lg" title="Manage Stocks" align='center' x-cloak x-on:close="$dispatch('clearAddStockFormModalData')" blurless wire:ignore.self>
    @if($variations)
        <div class="flex flex-col gap-2 items-start text-gray-600 overflow-auto">
            @if($variations)
                @foreach ($variations as $key => $variation)
                    <x-maskable label="Variation: {{ $variation->name }}" corner="Current Stock: x{{ $variation->stocks }}" icon="truck" mask="####" wire:model="stocks.{{ $key }}.stocks" shadowless />
                @endforeach
            @endif
            
            <x-slot name="footer" class="flex justify-end gap-x-4">
                <x-button flat label="Cancel" x-on:click="close" />
                <x-button wire:loading.attr="disabled" wire:click="store" spinner="store" label="Restock" />
            </x-slot>
        </div>
    @else
        <div class="flex items-center justify-center">
            <div class="flex items-row items-center justify-center gap-3">
                <x-icon name='arrow-path' class="h-5 w-5 animate-spin"/>

                <span>
                    Fetching Data...
                </span>
            </div>
        </div>
    @endif
</x-modal-card>