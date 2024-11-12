<x-modal-card name="productSuspensionModal" title="Product Report" width="lg" align='center' x-cloak x-on:close="$dispatch('clearProductSuspensionModalFormData')" blurless wire:ignore.self> 
    @if($report) 
        <div class="flex flex-col gap-2 items-start text-gray-600">
            <div class="flex flex-col gap-2 w-full">
                <x-select label="Reason for Suspension" x-on:selected="Livewire.dispatch('updatedSuspensionReason')" wire:model='reason' placeholder="Please select an action" shadowless>
                    <x-select.option label="Inaccurate Product Description" value="Inaccurate Product Description" />
                    <x-select.option label="Counterfeit Product" value="Counterfeit Product" />
                    <x-select.option label="Poor Product Quality" value="Poor Product Quality" />
                    <x-select.option label="False Advertising or Misleading Claims" value="False Advertising or Misleading Claims" />
                    <x-select.option label="Suspicious or Fraudulent Activity" value="Suspicious or Fraudulent Activity" />
                    <x-select.option label="Customer Complaints or Negative Feedback" value="Customer Complaints or Negative Feedback" />
                    <x-select.option label="Others" value="Others" />
                </x-select>

                @if($reason == 'Others')
                    <x-textarea label="Description" wire:model="description" fluid shadowless placeholder="Type here" />   
                @endif
            </div>
            
            <x-slot name="footer" class="flex justify-end gap-x-4">
                <x-button wire:loading.attr="disabled" flat label="Cancel" x-on:click="close" />
                <x-button wire:loading.attr="disabled" wire:click="confirmSuspend" spinner="suspendProducts" label="Proceed" />
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