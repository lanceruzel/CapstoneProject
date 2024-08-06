<x-modal-card name="returnUpdateTrackingModal" width='sm' title="Update Tracking Number" align='center' x-cloak x-on:close="$dispatch('clearReturnUpdateTrackingModal')" blurless wire:ignore.self>  
    @if($returnRequest) 
        <div class="flex flex-col gap-2 items-start text-gray-600">
            @if($listOfCourriers)
                <x-select class="col-span-12 lg:col-span-4" label="Courrier" wire:model="courrier" placeholder="Select Courrier" :options="$listOfCourriers" searchable shadowless />
            @endif

            <x-input class="col-span-12 lg:col-span-8" label="Tracking Number" wire:model='trackingNumber' shadowless/>

            <x-slot name="footer" class="flex justify-end gap-x-4">
                <x-button flat label="Close" x-on:click="close" />

                @if($trackingNumber != null && $courrier != null)
                    <x-button wire:loading.attr="disabled" wire:click="updateTrackingNumber" spinner="updateTrackingNumber" label="Update" />
                @else
                    <x-button wire:loading.attr="disabled" wire:click="updateTrackingNumber" spinner="updateTrackingNumber" label="Add" />
                @endif
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