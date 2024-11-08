<tr class="bg-white">
    <td class="px-6 py-4">
        @if($status == null)
            <x-checkbox wire:model='isForCheckout' md wire:click="toggleIsForCheckout" />
        @else
            <x-checkbox wire:model='isForCheckout' md disabled />
        @endif
    </td>

    <td class="px-6 py-4">
        <div class="rounded-lg w-20 h-20" wire:ignore>
            <img src="{{ asset('uploads/products') . '/' . json_decode($cartItem->product->images)[0] }}" class="w-full h-full object-cover object-center rounded-t-lg" alt="...">
        </div>
    </td>

    <td class="py-4 align-top">
        <div class="flex flex-col items-start justify-start pt-1 min-w-[300px] max-w-[300px]">
            <div class="font-semibold break-words">
                <span>{{ $cartItem->product->name }}</span>
            </div>
    
            <p class="text-sm text-gray-600">
                <span class="font-medium">Variation: </span>{{ $cartItem->variation }}
            </p>

            @if($status != null)
                <x-badge flat negative class="mt-2" label="{{ $status }}" />
            @endif
        </div>
    </td>

    <td class="px-3 py-4 text-center min-w-[150px] max-w-[150px]">{{ App\Classes\CurrencyConverter::formatPrice($price * $cartItem->quantity) }}</td>

    <td class="px-6 py-4">
        <div class="flex items-center gap-x-3 p-3 justify-center w-[160px]" x-data="{
            quantity: @entangle('quantity'),
            plus() { 
                this.quantity++ 
            },
            minus() { 
                (quantity >= 2) ? this.quantity-- : this.quantity
            }
        }">
            @if($status == null)
                <x-mini-button rounded icon="minus" sm wire:click='minusQuantity'/>

                <p>x<span x-text="quantity"></span></p>

                <x-mini-button rounded icon="plus" sm wire:click='addQuantity'/>
            @else
                <x-mini-button rounded icon="minus" sm disabled />

                <p>x0</p>

                <x-mini-button rounded icon="plus" sm disabled />
            @endif
        </div>
    </td>

    <td class="-mr-1 px-6 py-4">
        <x-mini-button rounded icon="trash" negative wire:click='deleteCartItem' />
    </td>
</tr>