<x-modal-card name="shippingAddressForm" title="Manage Address" align='center' x-cloak x-on:close="$dispatch('clearshippingAddressFormModalData')" blurless wire:ignore.self>
    <div class="flex flex-col gap-2 items-start text-gray-600 overflow-auto">
        <x-input label="Recipient Name" wire:model="fullName" shadowless />
        <x-input label="House #. StreetName, Brgy." corner="Eg. 01 Magsaysay St., Landing" wire:model="address1" shadowless />
        <x-input label="Municipality, City, Country" corner="Eg. Limay, Bataan, Philippines" wire:model="address2" shadowless />
        <x-input label="Postal Code" corner="Eg. 2103" shadowless wire:model="postal" />
        <x-input label="Contact Number" shadowless wire:model="phoneNumber" />

        @if($addressUpdate != null)
            <x-slot name="footer" class="flex justify-between gap-x-4">
                <x-button label="Delete" right-icon="trash" interaction="negative" wire:click="deleteConfirmation" />
                
                <div class="space-x-3">
                    <x-button flat label="Cancel" x-on:click="close" />
                    <x-button wire:loading.attr="disabled" wire:click="store" spinner="store" label="Save" />
                </div>
            </x-slot>
        @else
            <x-slot name="footer" class="flex justify-end gap-x-4">
                <x-button flat label="Cancel" x-on:click="close" />
                <x-button wire:loading.attr="disabled" wire:click="store" spinner="store" label="Save" />
            </x-slot>
        @endif
    </div>
</x-modal-card>