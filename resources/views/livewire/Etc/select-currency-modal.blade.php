<x-modal-card name="changeCurrencyModal" width="lg" title="Change Currency View" align='center' x-cloak blurless wire:ignore.self>
    @if($currencies)
        <p class="font-medium mb-3">Currently Selected: <span class="font-normal">{{ auth()->user()->currency }}</span></p>
        <p class="mb-3">Rate as of {{ $date }}</p>

        <div class="grid grid-cols-3 gap-5 text-gray-600 overflow-y-auto p-5 h-fit max-h-[500px]">
            @foreach($currencies as $item)
                <div class="col-span-1 flex flex-col items-center just-center py-5 border shadow rounded-lg hover:bg-gray-100 cursor-pointer" wire:click="confirmation('{{ $item['currency'] }}')">
                    <p class="text-center w-full">{{ $item['currency'] }}</p>

                    @if($item['currency'] != 'USD')
                        <small class="text-center w-full inline-block">
                            @switch($item['currency'])
                                @case('USD')
                                    <span>$</span>
                                    @break
                                @case('PHP')
                                    <span>₱</span>
                                    @break
                                @case('EUR')
                                    <span>€</span>
                                    @break
                                @case('JPY')
                                    <span>¥</span>
                                    @break
                                @case('KRW')
                                    <span>₩</span>
                                    @break
                                @default
                            @endswitch
                            <span>{{ $item['rate'] }}</span>
                        </small>
                    @else
                        <small class="text-center w-full inline-block">Base</small>
                    @endif
                </div>
            @endforeach
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