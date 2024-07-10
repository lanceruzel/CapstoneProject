<x-modal-card name="productFeedbackFormModal" width='sm' title="Product Feeback and Rating" align='center' x-cloak x-on:close="$dispatch('clearProductFeedbackForModalData')" blurless wire:ignore.self>  
   @if($orderedItem) 
        <div class="flex flex-col gap-2 items-start text-gray-600 overflow-auto">
            <div class="font-semibold w-full text-center">
                {{ $product->name }}
            </div>

            <div class="w-full">
                <x-input label="Rating" wire:model="productRating" placeholder="0.0 - 5.0" shadowless />
            </div>
            
            <x-textarea wire:model="feedbackContent" label="Feedback" shadowless placeholder="Write your review here" />

            <x-slot name="footer" class="flex justify-end gap-x-4">
                <x-button flat label="Close" x-on:click="close" />
                <x-button wire:loading.attr="disabled" wire:click="store" spinner="store" label="Submit" />
            </x-slot>
        </div>
    @else
        <div class="flex items-center justify-center w-full">
            <div class="flex items-row items-center justify-center gap-3">
                <x-icon name='arrow-path' class="h-5 w-5 animate-spin"/>

                <span>
                    Loading...
                </span>
            </div>
        </div>
    @endif
</x-modal-card>