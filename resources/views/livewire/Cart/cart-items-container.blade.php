<div class="w-full flex flex-col items-center justify-center">
    @if(count($products))
        @foreach($products as $seller => $items)
            <div class="bg-white w-full mt-4 shadow overflow-auto px-3">
                <div class="border-b p-3 text-lg flex items-center gap-2">
                    <p class="font-semibold">{{ $seller }}</p>

                    <x-icon name="chevron-right" class="w-5 h-5" />
                </div>

                <table class="w-full">
                    <tbody class="divide-y">
                        @foreach ($items as $key => $item)
                            <livewire:Cart.cart-item-container :id="$item->id" wire:key="{{ $key }}-cart-{{ $item->id }}">
                        @endforeach
                    </tbody>
                </table>
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