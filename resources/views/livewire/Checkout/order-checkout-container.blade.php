<tr class="bg-white">
    <td class="px-6 py-4">
        <div class="rounded-lg w-16 h-16" wire:ignore>
            <img src="{{ asset('uploads/products') . '/' . json_decode($order->product->images)[0] }}" class="w-full h-full object-cover object-center rounded-t-lg" alt="...">
        </div>
    </td>

    <td class="py-4 align-top w-full">
        <div class="flex flex-col items-start justify-start pt-1">
            <p class="font-semibold break-words">
                {{ $order->product->name }}
            </p>
    
            <p class="text-sm text-gray-600">
                Variation: {{ $order->variation }}
            </p>
        </div>
    </td>

    <td class="px-3 py-4 text-center min-w-[80px] max-w-[80px]">{{ App\Classes\CurrencyConverter::formatPrice($order->getTotal()) }}</td>

    <td class="px-6 py-4 text-center min-w-[80px] max-w-[80px]">x {{ $order->quantity }}</td>
</tr>