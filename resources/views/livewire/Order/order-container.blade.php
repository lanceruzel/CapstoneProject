<div class="bg-white p-5 rounded-lg shadow">
    <div class="flex justify-between border-b pb-3">
        <div class="flex max-sm:flex-col max-sm:items-start items-center max-sm:justify-start justify-center gap-0 sm:gap-3">
            <p class="text-xl font-semibold p-0">#{{ $order->id . ' ' . $order->seller->storeInformation->name }}</p>

            <div class="flex items-center justify-center max-sm:flex-col sm:gap-3">
                @if($order->is_paid) 
                    {{-- <x-button sm flat black label="View Receipt" onclick="$openModal('viewReceiptModal')" wire:click="$dispatch('view-receipt-order', { id: {{ $order->id }} })"/> --}}
                    <x-button sm flat black label="Download Receipt" wire:loading.attr="disabled" wire:click="downloadReceipt" spinner="downloadReceipt" />
                @endif
            </div> 

        </div>

        <p class="text-sm">{{ date_format($order->created_at, "M d, Y") }}</p>
    </div>

    <table class="table-auto w-full border-spacing-y-4 text-sm text-left mt-5 over">
        <thead>
            <tr>
                <th></th>
                <th>Product</th>
                <th class="text-center">Quantity</th>
                <th class="text-center">SubTotal</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($orderedProducts as $orderProduct)
                <tr>
                    <td class="px-6 py-4">
                        <div class="rounded-lg w-16 h-16 border" wire:ignore>
                            @if(App\Classes\FileTypeIdentifier::identify(json_decode($orderProduct->product->media)[0]) == 'video')
                                <video src="{{ asset('uploads/products') . '/' . json_decode($orderProduct->product->media)[0] }}" alt="video preview" class="w-full h-full object-cover object-center rounded-lg"></video>
                            @else
                                <img src="{{ asset('uploads/products') . '/' . json_decode($orderProduct->product->media)[0] }}" alt="image preview" class="w-full h-full object-cover object-center rounded-lg">
                            @endif
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

                            @if($orderProduct->product->status == App\Enums\Status::Suspended)
                                <x-badge flat negative class="mt-2" label="Currently suspended" />
                            @endif
                        </div>
                    </td>
                    
                    <td class="px-6 py-4 text-center">x{{ $orderProduct->quantity }}</td>
                    <td class="px-3 py-4 text-center">{{ App\Classes\CurrencyConverter::formatPrice($orderProduct->subtotal) }}</td>
                    <td class="px-6 py-4">
                        <div class="gap-3 flex flex-row items-center justify-center h-full">
                            @if($order->status == App\Enums\Status::OrderBuyerReceived)
                                @if(!$orderProduct->hasFeedback())
                                    <x-button label="Review" onclick="$openModal('productFeedbackFormModal')" wire:click="$dispatch('open-product-feedback', { id: {{ $orderProduct->id }} })" />
                                @endif
                            @endif
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{--  --}}
    <div class="border-t pt-3 flex items-center justify-between max-md:flex-col">
        <div>
            @if(($order->status == App\Enums\Status::OrderSellerCancel || $order->status == App\Enums\Status::OrderBuyerCancel) && ($order->cancel_reason != null || $order->cancel_reason != ''))
                @if($order->status == App\Enums\Status::OrderBuyerCancel)
                    <p>You cancelled this due to: {{ $order->cancel_reason }}</p>
                @elseif($order->status == App\Enums\Status::OrderSellerCancel)
                    <p>Seller cancelled  due to: {{ $order->cancel_reason }}</p>
                @else
                    <p>Cancelled: {{ $order->cancel_reason }}</p>
                @endif
            @else
                <p>{{ $order->status }} 
                    @if(($order->tracking_number != null || $order->tracking_number != '') && $order->status != App\Enums\Status::OrderBuyerReceived)
                        <x-link label="View Tracking" href="https://parcelsapp.com/en/tracking/{{ $order->tracking_number }}" target="_blank" />
                    @endif
                </p>
            @endif
        </div>

        <div class="flex items-center justify-center max-sm:flex-col gap-3 max-md:pt-3">
            @if($order->status == App\Enums\Status::OrderBuyerReceived)
                @if(!$hasReported)
                    <x-button negative outline label="Report" onclick="$openModal('productReportFormModal')" wire:click="$dispatch('get-order-info', { id: {{ $order->id }} })" />  
                @else
                    <x-button negative flat label="View Report" onclick="$openModal('viewReportDetailsModal')" wire:click="$dispatch('viewReportInformation', { id: {{ $order->id }} })" />  
                @endif
            @endif


            <div class="flex items-center justify-center gap-3">
                @if($order->status == App\Enums\Status::OrderSellerConfirmation)
                    <x-button outline negative wire:loading.attr="disabled" label="Cancel Order" onclick="$openModal('orderCancellationModalForm')" wire:click="$dispatch('cancellationOrder', { id: {{ $order->id }}, mode: 'buyer' })" />
                    {{-- <x-button outline negative wire:loading.attr="disabled" label="Cancel Order" wire:click="cancelOrderTest" /> --}}
                @endif
                
                <p class="font-bold">{{ App\Classes\CurrencyConverter::formatPrice($order->total) }}</p>
            </div>

            @if($order->status == App\Enums\Status::OrderSellerShipped)
                <x-button wire:loading.attr="disabled" label="Received" wire:click="orderReceivedConfirmation" />
            @endif
        </div>
    </div>
</div>