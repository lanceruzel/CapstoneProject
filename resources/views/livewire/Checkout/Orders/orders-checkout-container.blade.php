<div class="w-full bg-white p-5 rounded-lg shadow mt-3">
    <div class="space-y-3">
        <p class="font-semibold text-lg">Order Summary</p>
        @if(count($orders))
            @foreach($orders as $seller => $items)
                <div class="w-full mt-4 overflow-auto px-3">
                    <div class="border-b p-3 text-lg flex items-center gap-2">
                        <a href="{{ route('profile', $items['seller']->username) }}" class="font-semibold">{{ $seller }}</a>

                        <x-icon name="chevron-right" class="w-5 h-5" />
                    </div>

                    <table class="w-full">
                        <tbody class="divide-y">
                            @foreach ($items['items'] as $key => $item)
                                <livewire:Checkout.Orders.order-checkout-container :order="$item" :id="$item->id" wire:key="{{ $key }}-checkout-{{ $item->id }}">
                            @endforeach
                        </tbody>
                    </table>

                    <div class="flex items-end justify-center flex-col">
                        <p class="pb-3 font-medium">Total: ${{ number_format($totalPrices[$seller], 2) }}</p>
                        <x-input placeholder="Apply affiliate code" class="!w-[200px]" shadowless />
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

