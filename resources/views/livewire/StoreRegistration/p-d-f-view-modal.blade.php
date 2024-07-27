<x-modal-card name="pdfViewModal" title="PDF View" width='6xl' align='center' x-cloak x-on:close="$dispatch('clearpdfViewModalData')" blurless wire:ignore.self>
    @if($pdf)
        <div class="flex flex-col gap-2 items-start text-gray-600" wire:target='contact'>
            <iframe class="w-full h-[500px]" src="{{ $pdf }}" frameborder="0"></iframe>


            <x-slot name="footer" class="flex justify-end gap-x-4">
                <x-button flat label="Cancel" x-on:click="close" />
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