<x-modal-card name="viewOrdersModal" title="User Orders" width='4xl' persistent align='center' x-cloak x-on:close="$dispatch('clearViewOrdersModal')" blurless wire:ignore.self>  
    @if($orders)
        <div class="flex flex-col gap-2 items-start text-gray-600">
            <div class="flex items-center justify-between w-full">
                <p class="font-semibold text-xl">Product lists</p>
        
                <div class="w-60 flex items-center justify-center gap-3">
                    <x-dropdown>
                        <x-slot name="trigger">
                            <x-mini-button rounded icon="funnel" flat gray interaction="gray" />
                        </x-slot>
                    
                        <x-dropdown.header label="Filter">
                            <x-dropdown.item>
                                <x-checkbox label="For Review" wire:model.live="filterStatus" :value="App\Enums\Status::ForReview" />
                            </x-dropdown.item>
        
                            <x-dropdown.item>
                                <x-checkbox label="For Confirmation" wire:model.live="filterStatus" :value="App\Enums\Status::OrderSellerConfirmation" />
                            </x-dropdown.item>
        
                            <x-dropdown.item>
                                <x-checkbox label="Preparing" wire:model.live="filterStatus" :value="App\Enums\Status::OrderSellerPreparing" />
                            </x-dropdown.item>
        
                            <x-dropdown.item>
                                <x-checkbox label="Shipped" wire:model.live="filterStatus" :value="App\Enums\Status::OrderSellerShipped" />
                            </x-dropdown.item> 
        
                            <x-dropdown.item>
                                <x-checkbox label="Cancelled" wire:model.live="filterStatus" :value="App\Enums\Status::OrderSellerCancel" />
                            </x-dropdown.item> 
                        </x-dropdown.header>
                    </x-dropdown>
        
                    <x-input icon="magnifying-glass" wire:model.live.debounce.200ms="search" placeholder="Search" shadowless />
                </div>
            </div>
        
            <div class="w-full pt-5 overflow-auto flex items-center justify-center flex-col">
                <table class="table-auto w-full border-spacing-y-4 text-sm text-left">
                    <thead class="border-b-2">
                        <tr>
                            <th scope="col" class="px-6 py-3">Order #</th>
                            <th scope="col" class="px-6 py-3">Buyer</th>
                            <th scope="col" class="px-6 py-3">Payment Method</th>
                            <th scope="col" class="px-6 py-3">Payment Status</th>
                            <th scope="col" class="px-6 py-3">Status</th>
                            <th scope="col" class="px-6 py-3">Ordered Data</th>
                            <th scope="col" class="px-6 py-3"></th>
                        </tr>
                    </thead>

                    <tbody>
                        @if($orders && count($orders) > 0)
                            @foreach ($orders as $order)
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-100">
                                    <td class="px-6 py-4">{{ $order->id }}</td>
                                    <td class="px-6 py-4">{{ $order->name }}</td>
                                    <td class="px-6 py-4">
                                        <x-badge flat amber label="{{ $order->payment_method }}" />
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($order->is_paid)
                                            <x-badge flat positive label="Paid" />
                                        @else
                                            <x-badge flat info label="Not yet paid" />
                                        @endif
                                    </td>
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
                                    <td class="px-6 py-4">{{ date_format($order->created_at, "M d, Y") }}</td>
                                    <td class="px-6 py-4">
                                        <x-button icon="eye" label="View" onclick="$openModal('orderViewDetailsModal')" wire:click="$dispatch('viewOrderInfo', { id: {{ $order->id }}})"  />
                                    </td>
                                </tr>
                            @endforeach 
                        @endif
                    </tbody>
                </table>

                <!-- Pagination -->
                {{-- <div class="w-full mt-5">
                    {{ $orders->links() }}
                </div> --}}
        
                @if(count($orders) <= 0)
                    <div class="flex flex-col items-center justify-center mt-5">
                        <h1 class="text-2xl font-semibold">No records found</h1>
                        <img class="h-[400px]" src="{{ asset('assets/svg/no-data-2.svg') }}" alt="No data found"/>
                    </div>
                @endif
            </div>

            <x-slot name="footer" class="flex justify-end gap-x-4">
                <x-button wire:loading.attr="disabled" flat label="Close" x-on:click="close" />
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