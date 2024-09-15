<x-modal-card name="pdfViewModal" title="Document View" width='6xl' align='center' x-cloak x-on:close="$dispatch('clearpdfViewModalData')" blurless wire:ignore.self>
    @if($file)
        <div class="flex flex-col gap-2 items-start text-gray-600" wire:target='contact'>

            @if($fileType == 'pdf')
                <iframe class="w-full h-[500px]" src="{{ $file }}" frameborder="0"></iframe>
            @elseif($fileType == 'png' || $fileType == 'jpg' || $fileType == 'jpeg')
                <div class="flex items-center justify-center w-full">
                    <div class="w-[500px] h-full" uk-lightbox>
                        <a href="{{ $file }}">
                            <img src="{{ $file }}" alt="" class="w-full h-full object-fit">
                        </a>
                    </div>
                </div>
            @else
                <iframe class="w-full h-[500px]" src="https://view.officeapps.live.com/op/embed.aspx?src={{ $file }}" frameborder="0"></iframe> 
            @endif

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