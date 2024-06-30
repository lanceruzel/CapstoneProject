<tr class="bg-white">
    <td class="px-6 py-4">
        <x-checkbox wire:model='isForCheckout' md wire:click="toggleIsForCheckout" />
    </td>

    <td class="px-6 py-4">
        <div class="rounded-lg w-28 h-28" wire:ignore>
            <img src="{{ asset('uploads/products') . '/' . json_decode($cartItem->product->images)[0] }}" class="w-full h-full object-cover object-center rounded-t-lg" alt="...">
        </div>
    </td>

    <td class="py-4 align-top">
        <div class="flex flex-col items-start justify-start pt-1 min-w-[300px] max-w-[300px]">
            <p class="font-semibold break-words">
                {{ $cartItem->product->name }}
            </p>
    
            <p class="text-sm text-gray-600">
                Variation: {{ $cartItem->variation }}
            </p>
        </div>
    </td>

    <td class="px-3 py-4 text-center min-w-[150px] max-w-[150px]">${{ number_format($price * $cartItem->quantity, 2) }}</td>

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
            <x-mini-button rounded icon="minus" sm white interaction="white" wire:click='minusQuantity'/>

            <p>x 
                <span x-text="quantity"></span> 
            </p>

            <x-mini-button rounded icon="plus" sm white interaction="white" wire:click='addQuantity'/>
        </div>
    </td>

    <td class="-mr-1 px-6 py-4">
        <x-mini-button rounded icon="trash" flat gray interaction="negative" wire:click='deleteCartItem' />
    </td>
</tr>