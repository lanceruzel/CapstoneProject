<x-modal-card name="viewShippingAddressesModal" title="Saved Addresses" align='center' x-cloak x-on:close="$dispatch('clearAddStockFormModalData')" blurless wire:ignore.self>
    <div class="flex flex-col gap-2 items-start text-gray-600 overflow-auto">

        <!-- Addressess -->
        <div class="w-full h-full px-1 space-y-3 max-h-[400px]">
            @for ($i = 0; $i < 5; $i++)
                <x-radio wire:model="model1" value="">
                    <x-slot name="label" class="w-full">
                        <div class="!w-full border p-3 rounded-lg flex items-center justify-between">
                            <div>
                                <p class="text-lg">Lance Ruzel C. Ambrocio</p>
                                <p>Address 1</p>
                                <p>Address 2</p>
                                <p>09205524353</p>
                            </div>

                            <div>
                                <x-button white xs label="Edit" />
                            </div>
                        </div>
                    </x-slot>
                </x-radio>
            @endfor
        </div>

        <x-slot name="footer" class="flex justify-end gap-x-4">
            <x-button flat label="Cancel" x-on:click="close" />
            <x-button onclick="$openModal('shippingAddressForm')" label="Add Address" />
        </x-slot>
    </div>
</x-modal-card>