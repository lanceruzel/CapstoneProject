<div class="w-full flex flex-col items-center justify-center">
    @if(count($checkedOutSellers))
        @foreach($checkedOutSellers as $seller => $checkedOutSeller)
            <div class="bg-white w-full mt-4 shadow overflow-auto px-3">
                <div class="border-b p-3 text-lg flex items-center gap-2">
                    <a href="{{ route('profile', $checkedOutSeller['seller']->username) }}" class="font-semibold">{{ $seller }}</a>

                    <x-icon name="chevron-right" class="w-5 h-5" />
                </div>

                <table class="w-full">
                    <tbody class="divide-y">
                        @foreach ($checkedOutSeller['products'] as $key => $product)
                            <livewire:Cart.cart-item-container :id="$product->id" wire:key="{{ $key }}-cart-{{ $product->id }}">
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endforeach
    @else
        <tr>
            <td colspan="8" class="text-center px-6 py-4 bg-gray-50">
                <div class="flex flex-col items-center justify-center mt-5">
                    <h1 class="text-2xl font-semibold">No products found</h1>
                    <img class="h-[400px]" src="{{ asset('assets/svg/no-data-2.svg') }}" alt="No data found"/>
                </div>
            </td>
        </tr>
    @endif
</div>