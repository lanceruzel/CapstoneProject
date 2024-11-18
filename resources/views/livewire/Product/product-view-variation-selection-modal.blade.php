<x-modal-card name="variationSelectionModal" width="lg" title="Add to Cart" align='center' x-cloak x-on:close="$dispatch('clearVariationSelectionData')" blurless wire:ignore.self>
    @if($variations)
        @if(count($variations) > 1)
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
                            <tr class="py-1.5">
                                <td class="text-center py-1">
                                    @if($variation->stocks > 0)
                                        <x-radio label="{{ $variation->name }}" wire:model.live="selectedVariation" value="{{ $variation->name }}" />
                                    @else
                                        <x-radio label="{{ $variation->name }}" disabled value="{{ $variation->name }}" />
                                    @endif
                                </td>
                                <td class="text-center">x{{ $variation->stocks }}</td>
                                <td class="text-center">{{ App\Classes\CurrencyConverter::formatPrice($variation->price) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div> 
        @endif

        <div>
            @if($selectedVariation != null)
                <div class="flex items-center gap-x-3 p-3 justify-center w-full">
                    <div>
                        <x-number min="1" max="1000" placeholder="0" label="Quantity:" wire:model="quantity" shadowless />
                    </div>
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