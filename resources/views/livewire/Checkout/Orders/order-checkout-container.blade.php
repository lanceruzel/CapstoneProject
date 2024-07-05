<tr class="bg-white">
    <td class="px-6 py-4">
        <div class="rounded-lg w-16 h-16" wire:ignore>
            <img src="{{ asset('uploads/products') . '/' . json_decode($order->product->images)[0] }}" class="w-full h-full object-cover object-center rounded-t-lg" alt="...">
        </div>
    </td>

    <td class="py-4 align-top">
        <div class="flex flex-col items-start justify-start pt-1 min-w-[300px] max-w-[300px]">
            <p class="font-semibold break-words">
                {{ $order->product->name }}
            </p>
    
            <p class="text-sm text-gray-600">
                Variation: {{ $order->variation }}
            </p>
        </div>
    </td>

    <td class="px-3 py-4 text-center min-w-[150px] max-w-[150px]">${{ number_format($order->getTotalPrice(), 2) }}</td>

    <td class="px-6 py-4">x {{ $order->quantity }}</td>
</tr>