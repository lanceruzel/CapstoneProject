<x-modal-card name="viewReceiptModal" title="Receipt" align='center' x-cloak x-on:close="$dispatch('clearReceiptModal')" blurless wire:ignore.self>  
    {{-- @if($order) --}}
        <div>

        </div>
    {{-- @else
        <div class="flex items-center justify-center w-full">
            <div class="flex items-row items-center justify-center gap-3">
                <x-icon name='arrow-path' class="h-5 w-5 animate-spin"/>

                <span>
                    Generating your receipt...
                </span>
            </div>
        </div>
    @endif --}}
</x-modal-card>