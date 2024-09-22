<x-modal-card name="returnCreateOrderModal" title="Return Create Order" align='center' x-cloak x-on:close="$dispatch('clearReturnCreateOrderModalData')" blurless wire:ignore.self>  
    @if($order && $request && $requestedItems)
        <div class="flex flex-col gap-2 items-start text-gray-600 overflow-auto">

            <table class="border border-gray-600 table-auto w-full border-spacing-y-4 text-left">
                <thead>
                    <tr>
                        <th class="p-2 font-medium border border-gray-300">Requested Items</th>
                        <th class="p-2 font-medium border border-gray-300">Variations</th>
                        <th class="p-2 font-medium border border-gray-300">Quantity</th>
                    </tr>
                </thead>
                <tbody class="w-full">
                    @foreach($requestedItems as $index => $item)
                        <tr>
                            <td class="p-2 font-medium border border-gray-300">
                                {{ $requestedItems[$index]['name'] }}
                            </td>

                            <td class="p-2 font-medium border border-gray-300">
                                <x-select label="" wire:model="requestedItems.{{ $index }}.selectedVariation" placeholder="Select variation" :options="$requestedItems[$index]['variation']" option-label="name" option-value="name"/>
                            </td>

                            <td class="p-2 border border-gray-300 min-w-[100px]">
                                <div class="flex items-center gap-x-3 p-3 justify-center w-full">
                                    <x-mini-button rounded icon="minus" white interaction="white" wire:click="decrementQuantity({{ $index }})"/>
                        
                                    <span>x 
                                        <span>{{ $item['quantity'] }}</span> 
                                    </span>
                
                                    <x-mini-button rounded icon="plus" white interaction="white" wire:click="incrementQuantity({{ $index }})"/>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>  
            
            <x-slot name="footer" class="flex justify-end gap-x-4">
                <x-button flat label="Cancel" x-on:click="close" />
                <x-button wire:loading.attr="disabled" wire:click="createOrder" spinner="createOrder" label="Create" />
            </x-slot>
        </div>
    @else
        <div class="flex items-center justify-center w-full">
            <div class="flex items-row items-center justify-center gap-3">
                <x-icon name='arrow-path' class="h-5 w-5 animate-spin"/>

                <span>
                    Fetching data...
                </span>
            </div>
        </div>
    @endif
</x-modal-card>
