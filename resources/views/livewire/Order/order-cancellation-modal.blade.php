<x-modal-card name="orderCancellationModalForm" title="Product Report" width="lg" align='center' x-cloak x-on:close="$dispatch('clearOrderCancellationModalFormData')" blurless wire:ignore.self> 
    @if($order && $mode) 
        <div class="flex flex-col gap-2 items-start text-gray-600">
            <div class="flex flex-col gap-2 w-full">
                @if($mode == 'store')
                    <x-select label="Reason for Cancelling" x-on:selected="Livewire.dispatch('updatedCancelReason')" wire:model='reason' placeholder="Please select an action" shadowless>
                        <x-select.option label="Missing part of the order" value="Missing part of the order" />
                        <x-select.option label="Out of stock" value="Out of stock" />
                        <x-select.option label="Incorrect pricing" value="Incorrect pricing" />
                        <x-select.option label="Buyer requested cancellation" value="Buyer requested cancellation" />
                        <x-select.option label="Payment issue" value="Payment issue" />
                        <x-select.option label="Shipping restriction" value="Shipping restriction" />
                        <x-select.option label="Fraudulent order" value="Fraudulent order" />
                        <x-select.option label="Store closed temporarily" value="Store closed temporarily" />
                        <x-select.option label="Unexpected high demand" value="Unexpected high demand" />
                        <x-select.option label="System error" value="System error" />
                        <x-select.option label="Others" value="Others" />
                    </x-select>
                @else
                    <x-select label="Reason for Cancelling" x-on:selected="Livewire.dispatch('updatedCancelReason')" wire:model='reason' placeholder="Please select an action" shadowless>
                        <x-select.option label="Ordered by mistake" value="Ordered by mistake" />
                        <x-select.option label="Found a better price" value="Found a better price" />
                        <x-select.option label="Changed my mind" value="Changed my mind" />
                        <x-select.option label="Delivery time too long" value="Delivery time too long" />
                        <x-select.option label="Ordered wrong item" value="Ordered wrong item" />
                        <x-select.option label="Item no longer needed" value="Item no longer needed" />
                        <x-select.option label="Financial reasons" value="Financial reasons" />
                        <x-select.option label="Delay in processing" value="Delay in processing" />
                        <x-select.option label="Purchased from another store" value="Purchased from another store" />
                        <x-select.option label="Shipping cost too high" value="Shipping cost too high" />
                        <x-select.option label="Concern about product quality" value="Concern about product quality" />
                        <x-select.option label="Others" value="Others" />
                    </x-select>
                @endif

                @if($reason == 'Others')
                    <x-textarea label="Description" wire:model="description" fluid shadowless placeholder="Type here" />   
                @endif
            </div>
            
            <x-slot name="footer" class="flex justify-end gap-x-4">
                <x-button wire:loading.attr="disabled" flat label="Cancel" x-on:click="close" />
                <x-button wire:loading.attr="disabled" wire:click="confirmation" spinner="cancelOrder" label="Proceed" />
            </x-slot>
        </div>
    @else
        <div class="flex items-center justify-center w-full">
            <div class="flex items-row items-center justify-center gap-3">
                <x-icon name='arrow-path' class="h-5 w-5 animate-spin"/>

                <span>
                    Fetching Data...
                </span>
            </div>
        </div>
    @endif
</x-modal-card>