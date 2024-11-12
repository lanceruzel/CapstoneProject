<x-modal-card name="orderViewDetailsModal" title="View User" persistent align='center' x-cloak x-on:close="$dispatch('clearOrderDetailsModal')" blurless wire:ignore.self>
    @if($order)
        <div class="flex flex-col gap-2 items-start text-gray-600" wire:target='contact'>
            <div class="relative overflow-x-auto w-full">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            Order Information
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
                                Buyer
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
                                    Paypal Reference Number
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
                                Shipping Fee: 
                            </th>
                            <td class="px-6 py-4">
                                $3.00
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
                                Deducted Total
                            </th>
                            <td class="px-6 py-4">
                                @if($order->commission)
                                    <span>${{ $order->total - $order->commission }}</span>
                                @else
                                    <span>${{ $order->total }}</span>
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>

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
            </div>

            <x-slot name="footer" class="flex justify-end gap-x-4">
                <x-button wire:loading.attr="disabled" flat label="Close" x-on:click="close" />
                {{-- <x-button wire:loading.attr="disabled" wire:click="updateRegistration" spinner="updateRegistration" label="Update" /> --}}
            </x-slot>
        </div>
    @else
        <div class="flex items-center justify-center">
            <div class="flex items-row items-center justify-center gap-3">
                <x-icon name='arrow-path' class="h-5 w-5 animate-spin"/>

                <span>
                    Fetching Data...
                </span>
            </div>
        </div>
    @endif
</x-modal-card>