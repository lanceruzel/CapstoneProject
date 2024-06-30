<div class="bg-white rounded-lg shadow">
    <div class="w-full overflow-auto">
        <table class="table-auto w-full border-spacing-y-4 text-sm text-left">
            <thead class="border-b-2">
                <tr>
                    <th scope="col" class="px-6 py-3"></th>
                    <th scope="col" class="px-6 py-3">Image</th>
                    <th scope="col" class="px-6 py-3">Product</th>
                    <th scope="col" class="px-6 py-3">Variation</th>
                    <th scope="col" class="px-6 py-3">Price</th>
                    <th scope="col" class="px-6 py-3">Quantity</th>
                    <th scope="col" class="px-6 py-3">Subtotal</th>
                    <th scope="col" class="px-6 py-3"></th>
                </tr>
            </thead>

            <tbody>
                @if(count($products))
                    @foreach ($products as $key => $item)
                        <livewire:Cart.cart-item-container :id="$item->id" wire:key="{{ $key }}-cart-{{ $item->id }}">
                    @endforeach
                @else
                    <tr>
                        <td colspan="8" class="text-center px-6 py-4 bg-gray-50">
                            No products found.
                        </td>
                    </tr>
                @endif

            </tbody>
        </table>
    </div>
</div>