<x-modal-card name="viewShippingAddressesModal" title="Saved Addresses" align='center' x-cloak x-on:close="$dispatch('clearAddStockFormModalData')" blurless wire:ignore.self>
    <div class="flex flex-col gap-2 items-start text-gray-600 overflow-auto">

        <!-- Addressess -->
        <div class="w-full h-full px-1 space-y-3 max-h-[400px]">
            @if(count($addresses) > 0)
                @foreach ($addresses as $address)
                    <x-radio wire:model="selectedAddress" value="{{ $address->id }}" wire:click="updateSelectedAddress">
                        <x-slot name="label" class="w-full">
                            <div class="!w-full border p-3 rounded-lg flex items-center justify-between">
                                <div>
                                    <p class="text-lg">{{ $address->full_name }}</p>
                                    <p>{{ $address->address_1 . ' ' . $address->address_2 . ' ' . $address->postal }}</p>
                                    <p>{{ $address->phone_number }}</p>
                                </div>

                                <div>
                                    <x-button white xs label="Edit" wire:click="$dispatch('view-address', { id: {{ $address->id }} })" onclick="$openModal('shippingAddressForm')"  />
                                </div>
                            </div>
                        </x-slot>
                    </x-radio>   
                @endforeach
            @else
                <p>Empty</p>
            @endif
        </div>

        <x-slot name="footer" class="flex justify-end gap-x-4">
            <x-button flat label="Cancel" x-on:click="close" />
            <x-button onclick="$openModal('shippingAddressForm')" label="Add New Address" />
        </x-slot>
    </div>
</x-modal-card>