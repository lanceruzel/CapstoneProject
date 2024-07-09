<div class="bg-white p-5 rounded-lg shadow">
    <div class="flex justify-between border-b pb-3">
        <p class="text-xl font-semibold">{{ $order->seller->storeInformation->name }}</p>

        <p class="text-sm">{{ $order->created_at }}</p>
    </div>

    <table class="table-auto w-full border-spacing-y-4 text-sm text-left mt-5 over">
        <thead>
            <tr>
                <th></th>
                <th>Product</th>
                <th class="text-center">Quantity</th>
                <th class="text-center">Price</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($orderedProducts as $orderProduct)
                <tr>
                    <td class="px-6 py-4">
                        <div class="rounded-lg w-16 h-16 border" wire:ignore>
                            <img src="{{ asset('uploads/products') . '/' . json_decode($orderProduct->product->images)[0] }}" class="w-full h-full object-cover object-center rounded-t-lg" alt="...">
                        </div>
                    </td>

                    <td class="py-4 align-top">
                        <div class="flex flex-col items-start justify-start pt-1 lg:min-w-[300px] lg:max-w-[300px]">
                            <p class="font-semibold break-words text-lg">
                                {{ $orderProduct->product->name }}
                            </p>
                    
                            <p class="text-sm text-gray-600">
                                Variation: {{ $orderProduct->variation }}
                            </p>
                        </div>
                    </td>
            
                    <td class="px-6 py-4 text-center">x{{ $orderProduct->quantity }}</td>
                    <td class="px-3 py-4 text-center">$4343</td>
                    <td class="px-6 py-4">
                        <div class="gap-3 flex flex-row items-center justify-center h-full">
                            <x-button negative label="Report" />
                            <x-button label="Review" />
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="border-t pt-3 flex items-center justify-between max-md:flex-col">
        <div>
            <p>{{ $order->status }} <x-link label="View Tracking" href="https://parcelsapp.com/en/tracking/{{ $order->tracking_number }}" target="_blank" /></p>
        </div>

        <div class="flex items-center justify-center flex-col gap-3 max-md:pt-3">
            <p class="font-bold">${{ number_format($order->total, 2) }}</p>
            <x-button label="Received" />
        </div>
    </div>

</div>