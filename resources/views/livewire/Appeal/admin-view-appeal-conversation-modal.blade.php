<x-modal-card name="reportAppealFormModal" title="Report Appeal Conversation" align='center' x-cloak x-on:close="$dispatch('clearReportAppealFormModalData')" blurless wire:ignore.self>  
    <div class="flex flex-col gap-2 items-start text-gray-600 overflow-auto">
        <div class="flex flex-col gap-2 w-full">
            @if($product)
                <div class="w-full">
                    <p class="font-semibold">Product: <span class="font-normal">{{ $product->name }}</span></p>
                    <p class="font-semibold">Seller: <span class="font-normal">{{ $seller }}</span></p>
                </div>
            @else
                <div class="flex items-center justify-center w-full">
                    <div class="flex items-row items-center justify-center gap-3">
                        <x-icon name='arrow-path' class="h-5 w-5 animate-spin"/>
        
                        <span>
                        Fetching data...
                        </span>
                    </div>
                </div>
            @endif

            <livewire:Messaging.conversation-container :selectedID='null' />
        </div>
        
        <x-slot name="footer" class="flex justify-end gap-x-4">
            @if($product && $product->status == App\Enums\Status::Suspended)
                <x-button wire:loading.attr="disabled" wire:click="unsuspendedConfirmation" spinner="unsuspendedConfirmation" label="Unsuspended Product" />
            @else
                <x-button wire:loading.attr="disabled" wire:click="unsuspendedConfirmation" spinner="unsuspendedConfirmation" label="Print Report" />
            @endif
        </x-slot>
    </div>
</x-modal-card>