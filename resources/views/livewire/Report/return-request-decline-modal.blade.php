<x-modal-card name="returnReuqestCancellationModal" title="Product Report" width="lg" align='center' x-cloak x-on:close="$dispatch('clearReturnReuqestCancellationModalFormData')" blurless wire:ignore.self> 
    @if($report) 
        <div class="flex flex-col gap-2 items-start text-gray-600">
            <div class="flex flex-col gap-2 w-full">
                <x-select label="Reason for declining the request" x-on:selected="Livewire.dispatch('updatedReturnReason')" wire:model='reason' placeholder="Please select an action" shadowless>
                    <x-select.option label="Return Policy Not Met" value="Return Policy Not Met" />
                    <x-select.option label="Product Was Used or Damaged by Buyer" value="Product Was Used or Damaged by Buyer" />
                    <x-select.option label="Return Window Expired" value="Return Window Expired" />
                    <x-select.option label="Item Not Purchased From Our Store" value="Item Not Purchased From Our Store" />
                    <x-select.option label="Product Not Damaged" value="Product Not Damaged" />
                    <x-select.option label="Incorrect Return Reason" value="Incorrect Return Reason" />
                    <x-select.option label="No Proof of Damage" value="No Proof of Damage" />
                    <x-select.option label="Used or Worn Product" value="Used or Worn Product" />
                    <x-select.option label="Non-Returnable Item" value="Non-Returnable Item" />
                    <x-select.option label="Others" value="Others" />
                </x-select>

                @if($reason == 'Others')
                    <x-textarea label="Description" wire:model="description" fluid shadowless placeholder="Type here" />   
                @endif
            </div>
            
            <x-slot name="footer" class="flex justify-end gap-x-4">
                <x-button wire:loading.attr="disabled" flat label="Cancel" x-on:click="close" />
                <x-button wire:loading.attr="disabled" wire:click="confirmation" spinner="declineRequest" label="Proceed" />
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