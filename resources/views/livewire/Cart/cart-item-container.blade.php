<tr class="bg-white border-b hover:bg-gray-50">
    <td class="px-6 py-4">
        <input type="checkbox" class="size-4 rounded-sm focus:ring-0 focus:border-gray-300 focus:outline-0" @if($cartItem->for_checkout) checked @endif wire:click="toggleIsForCheckout">
    </td>

    <td class="px-6 py-4">
        <div class="rounded-lg w-32 h-32" wire:ignore>
            <img src="{{ asset('uploads/products') . '/' . json_decode($cartItem->product->images)[0] }}" class="w-full h-full object-cover object-center rounded-t-lg" alt="...">
        </div>
    </td>

    <td class="px-6 py-4">{{ $cartItem->product->name }}</td>
    {{-- <td class="px-6 py-4">{{ $cartItem->product->post->user->type != UserType::Store ? $cartItem->product->post->user->StoreInfo->name : $cartItem->product->post->user->userInfo->first_name . ' ' . $cartItem->product->post->user->userInfo->last_name }} </td> --}}
    <td class="px-6 py-4">{{ $cartItem->variation }}</td>
    <td class="px-6 py-4">${{ $price }}</td>

    <td class="px-6 py-4">
        <div class="flex items-center gap-x-3 p-3 justify-center w-[140px]" x-data="{
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

    <td class="px-6 py-4">${{ $price * $cartItem->quantity }}</td>

    <td class="-mr-1 px-6 py-4">
        <x-mini-button rounded icon="trash" flat gray interaction="negative" wire:click='deleteCartItem' />
    </td>
</tr>