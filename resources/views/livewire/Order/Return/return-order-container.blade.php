<div class="bg-white p-5 rounded-lg shadow">
    <div class="flex justify-between border-b pb-3">
        <p class="text-xl font-semibold">{{ $order->seller->name() }}</p>

        <p class="text-sm">{{ date_format($order->created_at, "M d, Y") }}</p>
    </div>

    <table class="table-auto w-full border-spacing-y-4 text-sm text-left mt-5 over">
        <thead>
            <tr>
                <th></th>
                <th>Product</th>
                {{-- <th class="text-center">Quantity</th> --}}
            </tr>
        </thead>
        <tbody>
            @foreach ($orderedProducts as $orderProduct)
                <tr>
                    <td class="px-6 py-4">
                        <div class="rounded-lg w-16 h-16 border" wire:ignore>
                            <img src="{{ asset('uploads/products') . '/' . json_decode($orderProduct->images)[0] }}" class="w-full h-full object-cover object-center rounded-t-lg" alt="...">
                        </div>
                    </td>

                    <td class="py-4 align-top">
                        <div class="flex flex-col items-start justify-start pt-1 lg:min-w-[300px] lg:max-w-[300px]">
                            <p class="font-semibold break-words text-lg">
                                {{ $orderProduct->name }}
                            </p>
                    
                            <p class="text-sm text-gray-600">
                                @foreach ($order->order->orderedItems as $item)
                                    @if($item->product_id == $orderProduct->id)
                                        Variation: {{ $item->variation }}
                                    @endif
                                @endforeach
                            </p>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="border-t pt-3 flex items-center justify-between max-md:flex-col">
        <div>
            <p>
                @if($order->status == App\Enums\Status::Accepted)
                    <span>Seller has accepted your return request. </span>
                @elseif($order->status == App\Enums\Status::ReturnRequestBuyerShipped)
                    <span>You have shipped the item and is now waiting for seller to received. </span>
                @elseif($order->status == App\Enums\Status::ReturnRequestReceieved)
                    <span>Seller has received the item and now preparing to send you your item/s. </span>
                @elseif($order->status == App\Enums\Status::ReturnRequestSellerOrderCreated)
                    <span>Request has been fulfilled. </span>
                @else
                    {{ $order->status }}
                @endif

                @if($order->tracking_number != null && ($order->status != App\Enums\Status::ReturnRequestReceieved  &&  $order->status != App\Enums\Status::ReturnRequestSellerOrderCreated))
                    <x-link label="View Tracking" href="https://parcelsapp.com/en/tracking/{{ $order->tracking_number }}" target="_blank" />
                @endif
            </p>
        </div>

        <div class="flex items-center justify-center flex-col gap-3 max-md:pt-3">
            @if($order->status != App\Enums\Status::ReturnRequestSellerOrderCreated)
                @if($order->tracking_number != null && $order->courrier != null)
                    <x-button label="Update Tracking Number" onclick="$openModal('returnUpdateTrackingModal')" wire:click="$dispatch('req-return-info', { id: {{ $order->id }} })" />
                @else
                    <x-button label="Add Tracking Number" onclick="$openModal('returnUpdateTrackingModal')" wire:click="$dispatch('req-return-info', { id: {{ $order->id }} })" />
                @endif
            @endif
        </div>
    </div>
</div>