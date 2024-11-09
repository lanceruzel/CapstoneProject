<x-modal-card name="orderViewModal" title="Order Information" persistent align='center' x-cloak x-on:close="$dispatch('clearOrderViewModalData')" blurless wire:ignore.self>  
    @if($order)
        <div class="flex flex-col gap-2 items-start text-gray-600 overflow-auto">
 
            @if(($order->status == App\Enums\Status::OrderSellerCancel || $order->status == App\Enums\Status::OrderBuyerCancel) && ($order->cancel_reason != null || $order->cancel_reason != ''))
                <x-alert title="Cancelled due to: {{ $order->cancel_reason }}" negative />
            @endif

            <div class="relative overflow-x-auto w-full">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            Information
                        </th>
                    </tr>
                </thead>

                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <tbody>
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                Order Number
                            </th>
                            <td class="px-6 py-4">
                                {{ $order->id }}
                            </td>
                        </tr>
    
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                Name
                            </th>
                            <td class="px-6 py-4">
                                {{ $order->name }}
                            </td>
                        </tr>
    
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                Address
                            </th>
                            <td class="px-6 py-4">
                                {{ $order->address }}
                            </td>
                        </tr>
    
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                Postal Code
                            </th>
                            <td class="px-6 py-4">
                                {{ $order->postal }}
                            </td>
                        </tr>
                        
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                Contact Number
                            </th>
                            <td class="px-6 py-4">
                                {{ $order->contact }}
                            </td>
                        </tr>
    
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                Payment Method
                            </th>
                            <td class="px-6 py-4">
                                {{ $order->payment_method == 'COD' ? 'Cash on Delivery' : 'PayPal' }} {{ $order->payment_method == 'PayPal' ? '(Reference #:' . $order->referenceNumber . ')' : null }}
                            </td>
                        </tr>
    
                        @if($order->referenceNumber)
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    Payment Reference Number
                                </th>
                                <td class="px-6 py-4">
                                    {{ $order->referenceNumber }}
                                </td>
                            </tr>
                        @endif
    
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                Payment Status
                            </th>
                            <td class="px-6 py-4">
                                {{ $order->is_paid ? 'Paid' : 'Pending' }}
                            </td>
                        </tr>
    
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                Total
                            </th>
                            <td class="px-6 py-4">
                                ${{ number_format($order->total, 2) }}
                            </td>
                        </tr>
    
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                Applied Affiliate Code
                            </th>
                            <td class="px-6 py-4">
                                {{ $order->affiliate_code ? $order->affiliate_code : 'None' }}
                            </td>
                        </tr>
    
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                Commission
                            </th>
                            <td class="px-6 py-4">
                                {{ $order->commission ? '$'. $order->commission : 'None' }}
                            </td>
                        </tr>
    
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                Deducted Total
                            </th>
                            <td class="px-6 py-4">
                                @if($order->commission)
                                    <span>${{ number_format($order->total - $order->commission, 2) }}</span>
                                @else
                                    <span>${{ number_format($order->total, 2) }}</span>
                                @endif
                            </td>
                        </tr>
    
                        @if($order->status == App\Enums\Status::OrderSellerShipped)
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                </th>
                                <td class="px-6 py-4">
                                </td>
                            </tr>
    
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    Courrier
                                </th>
                                <td class="px-6 py-4">
                                    {{ $order->courrier }}
                                </td>
                            </tr>
    
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    Tracking Number
                                </th>
                                <td class="px-6 py-4">
                                    {{ $order->tracking_number }}
                                </td>
                            </tr>
                        @endif

                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                Status
                            </th>
                            <td class="px-6 py-4">
                                @if($order->status == App\Enums\Status::OrderSellerConfirmation)
                                    <span>Waiting for your confirmation.</span>
                                @elseif($order->status == App\Enums\Status::OrderSellerPreparing)
                                    <span>Waiting for shipment and tracking number.</span>
                                @elseif($order->status == App\Enums\Status::OrderSellerShipped)
                                    <span>Order has been shipped and waiting for buyer to be received.</span>
                                @elseif($order->status == App\Enums\Status::OrderSellerCancel)
                                    <span>You cancelled this order.</span>
                                @elseif($order->status == App\Enums\Status::OrderBuyerReceived)
                                    <span>Buyer have received the order.</span>
                                @elseif($order->status == App\Enums\Status::OrderBuyerCancel)
                                    <span>Buyer cancelled this order.</span>
                                @else
                                    <span>{{ $order->status }}</span>
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            @if($order->status == App\Enums\Status::OrderSellerPreparing)
                <div class="w-full grid grid-cols-12 gap-3 mt-3">
                    <x-select class="col-span-12 lg:col-span-4" label="Courrier" wire:model="courrier" placeholder="Select Courrier" :options="$listOfCourriers" searchable shadowless />
                    <x-input class="col-span-12 lg:col-span-8" label="Tracking Number" wire:model='trackingNumber' shadowless/>
                </div>
            @endif

            <div>
                <p class="font-bold pt-5 w-full text-center">Ordered Items</p>

                <div class="w-full overflow-auto">
                    <table class="table-auto w-full border-spacing-y-4 text-sm text-left">
                        <thead class="border-b-2">
                            <tr>
                                <th scope="col" class="px-6 py-3">Product</th>
                                <th scope="col" class="px-6 py-3">Variation</th>
                                <th scope="col" class="px-6 py-3">Stocks Available</th>
                                <th scope="col" class="px-6 py-3">Quantity</th>
                                <th scope="col" class="px-6 py-3">SubTotal</th>
                            </tr>
                        </thead>

                        <tbody>
                            @if(count($products) > 0)
                                @foreach ($products as $item)
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-100">
                                        <td class="px-6 py-4">{{ $item->product->name }}</td>
                                        <td class="px-6 py-4">{{ $item->variation }}</td>
                                        <td class="px-6 py-4 text-center">
                                            @php
                                                $stocks = $item->product->getStocks($item->variation);
                                            @endphp

                                            @if($stocks >= 100)
                                                <x-badge flat positive label="x{{ $stocks }}" />
                                            @elseif($stocks >= 50 && $stocks < 100)
                                                <x-badge flat lime label="x{{ $stocks }}" />
                                            @elseif($stocks >= 25 && $stocks < 50)
                                                <x-badge flat amber label="x{{ $stocks }}" />
                                            @elseif($stocks >= 10 && $stocks < 25)
                                                <x-badge flat pink label="x{{ $stocks }}" />
                                            @else
                                                <x-badge flat negative label="x{{ $stocks }}" />
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-center">x{{ $item->quantity }}</td>
                                        <td class="px-6 py-4 text-center">${{ number_format($item->subtotal, 2) }}</td>
                                    </tr>
                                @endforeach 
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
            
            <x-slot name="footer" class="flex justify-end gap-x-4">
                <x-button wire:loading.attr="disabled" flat label="Close" x-on:click="close" />

                @if($order->status == App\Enums\Status::OrderSellerConfirmation)
                    <div class="w-full flex justify-end gap-x-4">
                        {{-- <x-button outline negative wire:loading.attr="disabled" wire:click="declineConfirmation" spinner="declineOrder" label="Decline Order" /> --}}
                        <x-button outline negative wire:loading.attr="disabled" label="Decline Order" onclick="$openModal('orderCancellationModalForm')" wire:click="$dispatch('cancellationOrder', { id: {{ $order->id }}, mode: 'store' })" />
                        <x-button wire:loading.attr="disabled" wire:click="acceptOrder" spinner="acceptOrder" label="Accept Order" />
                    </div>
                @elseif($order->status == App\Enums\Status::OrderSellerPreparing)
                    <x-button wire:loading.attr="disabled" wire:click="updateTrackingNumber" spinner="updateTrackingNumber" label="Update Tracking Number" />
                @endif
            </x-slot>
        </div>
    @else
        <div class="flex items-center justify-center w-full">
            <div class="flex items-row items-center justify-center gap-3">
                <x-icon name='arrow-path' class="h-5 w-5 animate-spin"/>

                <span>
                    Fetching Data...
                </span>
            </div>
        </div>
    @endif
</x-modal-card>