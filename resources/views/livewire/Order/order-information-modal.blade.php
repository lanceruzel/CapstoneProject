<x-modal-card name="orderViewModal" title="Order Information" align='center' x-cloak x-on:close="$dispatch('clearOrderViewModalData')" blurless wire:ignore.self>  
    @if($order)
        <div class="flex flex-col gap-2 items-start text-gray-600 overflow-auto">
            <p class="font-bold pt-5 w-full text-center">Order Information</p>

            <table class="table-auto w-full border-spacing-y-4 text-left">
                <tbody>
                    <tr>
                        <td class="text-right pe-3 font-medium">Order Number:</td>
                        <td>{{ $orderNumber }}</td>
                    </tr>

                    <tr>
                        <td class="text-right pe-3 font-medium">Name:</td>
                        <td>{{ $name }}</td>
                    </tr>

                    <tr>
                        <td class="text-right pe-3 font-medium">Address:</td>
                        <td>{{ $address }}</td>
                    </tr>

                    <tr>
                        <td class="text-right pe-3 font-medium">Postal Code:</td>
                        <td>{{ $postal }}</td>
                    </tr>
                    
                    <tr>
                        <td class="text-right pe-3 font-medium">Contact Number:</td>
                        <td>{{ $contact }}</td>
                    </tr>

                    <tr>
                        <td class="text-right pe-3 font-medium">Payment Method:</td>
                        <td>{{ $paymentMethod }}</td>
                    </tr>

                    <tr>
                        <td class="text-right pe-3 font-medium">Payment Status:</td>
                        <td>{{ $paymentStatus }}</td>
                    </tr>

                    <tr>
                        <td class="text-right pe-3 font-medium">Total:</td>
                        <td>{{ $total }}</td>
                    </tr>

                    <tr>
                        <td class="text-right pe-3 font-medium">Applied Affiliate Code:</td>
                        <td>{{ $affiliateCode ? $affiliateCode : 'None' }}</td>
                    </tr>

                    @if($order->status == App\Enums\Status::OrderSellerShipped)
                        <tr>
                            <td></td>
                            <td></td>
                        </tr>

                        <tr>
                            <td class="text-right pe-3 font-medium">Courrier:</td>
                            <td>{{ $order->courrier }}</td>
                        </tr>

                        <tr>
                            <td class="text-right pe-3 font-medium">Tracking Number:</td>
                            <td>{{ $order->tracking_number }}</td>
                        </tr>
                    @endif
                </tbody>
            </table>

            @if($order->status == App\Enums\Status::OrderSellerPreparing)
                <div class="w-full grid grid-cols-12 gap-3">
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
                                        <td class="px-6 py-4 text-center">x{{ $item->product->getStocks($item->variation) }}</td>
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
                <x-button flat label="Close" x-on:click="close" />

                @if($order->status == App\Enums\Status::OrderSellerConfirmation)
                    <div class="w-full flex justify-end gap-x-4">
                        <x-button outline negative wire:loading.attr="disabled" wire:click="declineConfirmation" spinner="declineOrder" label="Decline Order" />
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