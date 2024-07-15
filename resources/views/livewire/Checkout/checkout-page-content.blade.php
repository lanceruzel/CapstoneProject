<div>
    <!-- Shipping -->
    <div class="w-full bg-white p-5 rounded-lg shadow mt-3">
        <div class="space-y-3">
            <p class="font-semibold text-lg">Shipping Information</p>
        
            <div class="border-2 w-full p-5 rounded-lg space-y-0">
    
                @if($shippingInformation != null)
                    <div class="flex flex-col">
                        <p class="font-medium text-lg">{{ $shippingInformation[0]->full_name }}</p>
                        <p>{{ $shippingInformation[0]->address_1 . ', ' . $shippingInformation[0]->address_2 . ', ' . $shippingInformation[0]->postal }}</p>
                        <p>+{{ $shippingInformation[0]->phone_number }}</p>
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
    
    <!-- Orders -->
    <div class="w-full bg-white p-5 rounded-lg shadow mt-3">
        <div class="space-y-3">
            <p class="font-semibold text-lg">Order Summary</p>
            @if(count($checkedOutSellers))
                @foreach($checkedOutSellers as $seller => $checkedOutSeller)
                    <div class="w-full mt-4 overflow-auto px-3">
                        <div class="border-b p-3 text-lg flex items-center gap-2">
                            <a href="{{ route('profile', $checkedOutSeller['seller']->username) }}" class="font-semibold">{{ $seller }}</a>
    
                            <x-icon name="chevron-right" class="w-5 h-5" />
                        </div>
    
                        <table class="w-full">
                            <tbody class="divide-y">
                                @foreach ($checkedOutSeller['products'] as $key => $product)
                                    <livewire:Checkout.order-checkout-container :order="$product" :id="$product->id" wire:key="{{ $key }}-checkout-{{ $product->id }}">
                                @endforeach
                            </tbody>
                        </table>
    
                        <div class="flex items-end justify-center flex-col">
                            <p class="pb-3 font-medium">Total: ${{ number_format($checkedOutSeller['total'], 2) }}</p>
                            <x-input placeholder="Apply affiliate code" class="!w-[200px]" shadowless wire:model="affiliate.{{ $checkedOutSeller['seller']->id }}" />
                        </div>
                    </div>
                @endforeach
            @else
                <tr>
                    <td colspan="8" class="text-center px-6 py-4 bg-gray-50">
                        No products found.
                    </td>
                </tr>
            @endif
        </div>
    </div>
    
    <!-- Payment -->
    <div class="w-full bg-white p-5 rounded-lg shadow mt-3">
        <div class="space-y-3">
            <div>
                <p class="font-semibold text-lg">Payment Method</p>
            </div>
    
            <div class="flex flex-row gap-3 flex-wrap max-md:justify-center">
                <div class="border-2 p-5 rounded-lg cursor-pointer hover:bg-slate-50 active:scale-95 transition-all">
                    <div class="flex items-center justify-center gap-1">
                        <span>
                            <i class="ri-cash-line ri-lg"></i>
                        </span>
        
                        <p class="text-sm font-semibold">Cash on delivery</p>
                    </div>
                </div>
    
                <div class="border-2 p-5 rounded-lg cursor-pointer hover:bg-slate-50 active:scale-95 transition-all">
                    <div class="flex items-center justify-center gap-1">
                        <span>
                            <i class="ri-paypal-line ri-lg"></i>
                        </span>
    
                        <p class="text-sm font-semibold">Paypal</p>
                    </div>
                </div>
    
                <div wire:ignore>
                    <div id="paypal-button-container"></div>
                    <p id="result-message"></p>
                </div>

                {{-- <div class="border-2 p-5 first-line:p-5 rounded-lg cursor-pointer hover:bg-slate-50 active:scale-95 transition-all">
                    <div class="flex items-center justify-center gap-1">
                        <span>
                            <i class="ri-bank-card-2-line ri-lg"></i>
                        </span>
    
                        <p class="text-sm font-semibold">Credit/Debit Card</p>
                    </div>
                </div> --}}
            </div>
        </div>
    </div> 

    <!-- Order Summary -->
    <div class="w-full bg-white p-5 rounded-lg shadow space-y-3 flex flex-col items-end justify-center mt-3">
        <table class="border-separate border-spacing-3">
            <tbody>
                <tr>
                    <td class="text-end">Merchandise Subtotal:</td>
                    <td>${{ number_format($merchandiseTotal, 2) }}</td>
                </tr>
    
                {{-- <tr>
                    <td class="text-end">Tax Total:</td>
                    <td>₱3232 (2%)</td>
                </tr> --}}
    
                <tr>
                    <td class="text-end">Shipping Total:</td>
                    <td>${{ $shippingTotal }}</td>
                </tr>
    
                <tr>
                    <td class="text-end">Total Payment:</td>
                    <td class="text-xl font-semibold">${{ number_format($merchandiseTotal + $shippingTotal, 2) }}</td>
                </tr>
            </tbody>
        </table>
    
        <x-button class="!px-10" wire:loading.attr="disabled" wire:click='placeOrder' label="Place Order" />
    </div> 
</div>
