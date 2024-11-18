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
                    {{-- <div>
                        <x-number min="1" max="1000" placeholder="0" label="Quantity:" wire:model="quantity" shadowless />
                    </div> --}}
                    <div class="flex items-center justify-center gap-2">
                        <div class="flex flex-col items-center justify-center gap-1">
                            <div class="flex mb-1 justify-between items-end">
                                <label class="block text-sm font-medium disabled:opacity-60 text-gray-700 dark:text-gray-400 invalidated:text-negative-600 dark:invalidated:text-negative-700" for="value">
                                    Quantity
                                </label>
                            </div>
                            
                            <div class="flex justify-center items-stretch gap-1" x-data="{
                                value: @entangle('quantity'),
                                min: 1,
                                max: 1000,
                                step: 1,
                                increment() {
                                    this.checkValue();
                                    this.value = (this.value >= this.min && this.value < this.max) ? parseInt(this.value) + this.step : this.value;
                                },
                                decrement() {
                                    this.checkValue();
                                    this.value = (this.value > this.min) ? parseInt(this.value) - this.step : this.value;
                                },
                                checkValue(){
                                    if (!this.value || isNaN(this.value) || this.value == '') {
                                        this.value = this.min; // Reset to minimum value if empty or invalid
                                    }
                                }
                            }">
                                <x-button icon="minus" x-on:click="decrement()" x-bind:disabled="value <= min" />

                                <label class="rounded-md focus-within:ring-primary-600 bg-background-white dark:bg-background-dark relative flex justify-between gap-x-2 items-center transition-all ease-in-out duration-150 ring-1 ring-inset ring-gray-300 focus-within:ring-2 outline-0 pl-3 pr-3 py-2 invalidated:bg-negative-50 invalidated:ring-negative-500 invalidated:dark:ring-negative-700 invalidated:dark:bg-negative-700/10 invalidated:dark:ring-negative-600">
                                    <input class="!w-[50px] !text-center bg-transparent block border-0 text-gray-900 dark:text-gray-400 p-0 outline-none ring-0 sm:text-sm sm:leading-6 focus:ring-0 focus:border-0 placeholder:text-gray-400 dark:placeholder:text-gray-300 invalidated:text-negative-800 invalidated:dark:text-negative-600 invalidated:placeholder-negative-400 invalidated:dark:placeholder-negative-600/70" 
                                        type="number"
                                        x-model="value"
                                        min="1" max="1000"
                                        x-on:blur="checkValue()"
                                        x-on:input="checkValue()"
                                    >
                                </label>

                                <x-button icon="plus" x-on:click="increment()" x-bind:disabled="value >= max" />
                            </div>
                        </div>
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