<x-modal-card name="reportAppealFormModal" title="Report Appeal Conversation" align='center' x-cloak x-on:close="$dispatch('clearReportAppealFormModalData')" blurless wire:ignore.self>  
    @if($this->product)
        <div class="flex flex-col gap-2 items-start text-gray-600 overflow-auto">
            <div class="flex flex-col gap-2 w-full">
                <div class="w-full">
                    <p class="font-semibold">Product: </p>
                    <p>{{ $product->name }}</p>
                    <p class="font-semibold">Seller: </p>
                    <p>{{ $seller }}</p>
                </div>

                <livewire:Messaging.conversation-container :selectedID='null' />
            </div>
            
            @if($product->status == App\Enums\Status::Suspended)
                <x-slot name="footer" class="flex justify-end gap-x-4">
                    <x-button wire:loading.attr="disabled" wire:click="unsuspendedConfirmation" spinner="unsuspendedConfirmation" label="Unsuspended Product" />
                </x-slot>
            @endif
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
</x-modal-card>