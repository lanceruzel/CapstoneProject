<div class="w-full bg-white p-5 rounded-lg shadow mt-3">
    <div class="space-y-3">
        <p class="font-semibold text-lg">Shipping Information</p>
    
        <div class="border-2 w-full p-5 rounded-lg space-y-0">

            @if($address != null)
                <div class="flex flex-col">
                    <p class="font-medium text-lg">{{ $address[0]->full_name }}</p>
                    <p>{{ $address[0]->address_1 . ', ' . $address[0]->address_2 . ', ' . $address[0]->postal }}</p>
                    <p>+{{ $address[0]->phone_number }}</p>
                </div>

                <div class="flex items-center justify-end">
                    <x-button white sm label="Select Address" onclick="$openModal('viewShippingAddressesModal')"  />
                </div>
            @else
                <div class="w-full flex items-center justify-center">
                    <x-button light secondary label="Select Address" onclick="$openModal('viewShippingAddressesModal')" />
                </div>
            @endif
        </div>
    </div>
</div>

