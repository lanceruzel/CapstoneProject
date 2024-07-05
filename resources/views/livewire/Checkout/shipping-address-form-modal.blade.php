<x-modal-card name="shippingAddressForm" title="Manage Address" align='center' x-cloak x-on:close="$dispatch('clearAddStockFormModalData')" blurless wire:ignore.self>
    <div class="flex flex-col gap-2 items-start text-gray-600 overflow-auto">
        <x-input label="Recipient Name" shadowless />
        <x-input label="House #. StreetName, Brgy." corner="Eg. 01 Magsaysay St., Landing" shadowless />
        <x-input label="Municipality, City, Country" corner="Eg. Limay, Bataan, Philippines" shadowless />
        <x-input label="Zip Code" corner="Eg. 2103" shadowless />
        <x-input label="Contact Number" shadowless />

        <x-slot name="footer" class="flex justify-end gap-x-4">
            <x-button flat label="Cancel" x-on:click="close" />
            <x-button wire:loading.attr="disabled" wire:click="store" spinner="store" label="Save" />
        </x-slot>
    </div>
</x-modal-card>