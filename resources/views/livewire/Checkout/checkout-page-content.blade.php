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
    
    <div class="grid grid-cols-12 grid-rows-12 gap-3" x-on:totalUpdated="$refresh">
        <!-- Orders -->
        <div class="col-span-12 lg:col-span-8 bg-white p-5 rounded-lg shadow mt-3">
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
                        </div>

                        <div class="flex items-end justify-center flex-col">
                            <div class="pb-3 text-end">
                                <p class="font-medium">
                                    Total: @isset($checkedOutSeller['original_total']) 
                                                <span class="line-through">{{ App\Classes\CurrencyConverter::formatPrice($checkedOutSeller['original_total']) }}</span> 
                                            @endisset 
                                        {{ App\Classes\CurrencyConverter::formatPrice($checkedOutSeller['total']) }}
                                </p>
                                
                                @isset($checkedOutSeller['discount']) 
                                    <small>{{ $checkedOutSeller['applied_discount'] }}% discount applied</small>
                                @endisset 
                            </div>

                            <div class="flex items-center justify-center gap-3">
                                @if(isset($checkedOutSeller['original_total']))
                                    <x-input disabled placeholder="Apply affiliate code" class="!w-[200px]" shadowless wire:model="affiliate.{{ $checkedOutSeller['seller']->id }}" />
                                @else
                                    <x-input placeholder="Apply affiliate code" class="!w-[200px]" shadowless wire:model="affiliate.{{ $checkedOutSeller['seller']->id }}" />
                                @endif

                                @if(isset($checkedOutSeller['original_total']))
                                    {{-- <x-button negative flat wire:loading.attr="disabled" wire:click="removeAffiliate('{{ $checkedOutSeller['seller']->id }}')" label="Remove" /> --}}
                                        <x-button disabled flat label="Applied" />
                                @else
                                    <x-button wire:loading.attr="disabled" wire:target="applyAffiliate('{{ $checkedOutSeller['seller']->id }}')" wire:click="applyAffiliate('{{ $checkedOutSeller['seller']->id }}')" label="Apply" />
                                @endif
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="flex flex-col items-center justify-center mt-5">
                        <h1 class="text-2xl font-semibold">No products found</h1>
                        <img class="h-[400px]" src="{{ asset('assets/svg/no-data-2.svg') }}" alt="No data found"/>
                    </div>
                @endif
            </div>
        </div>

        <!-- Order Summary -->
        <div class="col-span-12 lg:col-span-4 ">
            <div class="bg-white p-5 rounded-lg shadow space-y-3 flex flex-col items-center justify-center mt-3">
                <table class="border-separate border-spacing-3">
                    <tbody>
                        <tr>
                            <td class="text-end">Subtotal:</td>
                            <td>{{ App\Classes\CurrencyConverter::formatPrice($merchandiseTotal) }}</td>
                        </tr>
            
                        {{-- <tr>
                            <td class="text-end">Affiliate Discount:</td>
                            <td>₱3232 (2%)</td>
                        </tr> --}}
            
                        <tr>
                            <td class="text-end">Shipping Total:</td>
                            <td>{{ App\Classes\CurrencyConverter::formatPrice($shippingTotal) }}</td>
                        </tr>
            
                        <tr>
                            <td class="text-end">Total Payment:</td>
                            <td class="text-xl font-semibold">{{ App\Classes\CurrencyConverter::formatPrice($merchandiseTotal + $shippingTotal) }}</td>
                        </tr>
                    </tbody>
                </table>
            
                <div class="flex gap-3 flex-col">
                    <x-button class="!px-10 w-full" wire:loading.attr="disabled" wire:click='placeOrder' label="Place Order | Cash On Delivery" />
                    
                    <div wire:ignore>
                        <div id="paypal-button-container"></div>
                        <p id="result-message"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
