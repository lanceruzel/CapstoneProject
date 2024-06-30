<x-modal-card name="variationSelectionModal" width="lg" title="Select Variation" align='center' x-cloak x-on:close="$dispatch('clearVariationSelectionData')" blurless wire:ignore.self>
    @if($variations)
        <div class="flex flex-row gap-3 items-center justify-center text-gray-600 overflow-auto px-3 py-2">
            <table class="table-auto w-full border-spacing-y-4 text-sm text-left">
                <thead class="border-b-2">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-center">Variation</th>
                        <th scope="col" class="px-6 py-3 text-center">Stocks Available</th>
                        <th scope="col" class="px-6 py-3 text-center">Price</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($variations as $key => $variation)
                            <tr>
                                <td class="text-center py-1">
                                    <x-radio label="{{ $variation->name }}" wire:model.live="selectedVariation" lg value="{{ $variation->name }}" />
                                </td>
                                <td class="text-center">x{{ $variation->stocks }}</td>
                                <td class="text-center">${{ number_format($variation->price, 2) }}</td>
                            </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
           
        <div>
            @if($selectedVariation != null)
                <div class="flex items-center gap-x-3 p-3 justify-center w-full" x-data="{
                    quantity: @entangle('quantity'),
                    plus() { 
                        this.quantity++ 
                    },
                    minus() { 
                        (quantity >= 2) ? this.quantity-- : this.quantity
                    }
                }">
                    <x-mini-button rounded icon="minus" white interaction="white" x-on:click="(quantity >= 2) ? quantity-- : quantity"/>
        
                    <span>x 
                        <span x-text="quantity"></span> 
                    </span>

                    <x-mini-button rounded icon="plus" white interaction="white" x-on:click="quantity++"/>
                </div>
            @endif

            <x-slot name="footer" class="flex justify-end gap-x-4">
                @if($selectedVariation != null)
                    <x-button wire:loading.attr="disabled" wire:click="addToCart" spinner="addToCart" label="Add to Cart" />
                @endif
            </x-slot>
        </div>
    @else
        <div class="flex items-center justify-center">
            <div class="flex items-row items-center justify-center gap-3">
                <x-icon name='arrow-path' class="h-5 w-5 animate-spin"/>

                <span>
                    Fetching Data...
                </span>
            </div>
        </div>
    @endif
</x-modal-card>