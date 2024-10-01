<x-modal-card name="viewReturnRequestModal" title="Return Request Details" align='center' x-cloak x-on:close="$dispatch('clearvViewReturnRequestModal')" blurless wire:ignore.self>  
    @if($request)
        <div class="flex flex-col gap-2 items-start text-gray-600 overflow-auto">
            @if($request->status == App\Enums\Status::ReturnRequestReview)
                <x-alert title="Waiting for your review" info />
            @elseif($request->status == App\Enums\Status::ReturnRequestBuyerShipped)
                <x-alert title="Item has been shipped by the seller" info />
            @elseif($request->status == App\Enums\Status::Accepted)
                <x-alert title="You have accepted this request and is now waiting for the buyer to ship the item/s" info />
            @elseif($request->status == App\Enums\Status::ReturnRequestReceieved)
                <x-alert title="You mark this as received." info />
            @elseif($request->status == App\Enums\Status::ReturnRequestSellerOrderCreated)
                <x-alert title="This request has been fulfilled." info />
            @elseif($request->status == App\Enums\Status::Declined)
                <x-alert title="You declined this request." info />
            @else
                <x-alert title="Waiting for your review." info />
            @endif

            <div class="flex flex-col gap-2 w-full">
                <div class="relative overflow-x-auto">
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
                                    Requested By
                                </th>
                                <td class="px-6 py-4">
                                    {{ $request->reporter->name() }}
                                </td>
                            </tr>

                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    Order ID
                                </th>
                                <td class="px-6 py-4">
                                    #{{ $request->order_id }}
                                </td>
                            </tr>

                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    Product/s
                                </th>
                                <td class="px-6 py-4">
                                    @foreach(json_decode($request->products) as $items)
                                        <p>{{ $items->name }}
                                            @if(!$loop->last),@endif
                                        </p>
                                    @endforeach
                                </td>
                            </tr>

                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    Reason
                                </th>
                                <td class="px-6 py-4">
                                    {{ ucfirst($request->reason) }}
                                </td>
                            </tr>

                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    Description
                                </th>
                                <td class="px-6 py-4">
                                    {{ $request->description }}
                                </td>
                            </tr>

                            @if($request->tracking_number != null && $request->courrier)
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">Courrier: </td>
                                    <td class="px-6 py-4">{{ $request->courrier  }}</td>
                                </tr>

                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">Tracking Number: </td>
                                    <td class="px-6 py-4">{{ $request->tracking_number  }} <x-link label="View Tracking" href="https://parcelsapp.com/en/tracking/{{ $request->tracking_number }}" target="_blank" /></td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                <x-errors only="images" />

                <p class="text-gray-600 mt-3">Images</p>

                @if($images)
                    <div class="max-w-full flex gap-4 overflow-x-auto p-3" uk-lightbox>
                        @foreach($images as $key => $image)
                            <div class="flex-shrink-0 w-56 h-56 relative">
                                <a href="{{ is_object($image) && method_exists($image, 'temporaryUrl') ? $image->temporaryUrl() : asset('uploads/report') . '/' . $image }}">
                                    <img src="{{ is_object($image) && method_exists($image, 'temporaryUrl') ? $image->temporaryUrl() : asset('uploads/report') . '/' . $image }}" alt="Uploaded Image" accept="image/png, image/jpeg" class="w-full h-full object-cover rounded-lg shadow border">
                                </a>
                            </div>  
                        @endforeach
                    </div>
                @endif 
            </div>
            
            <x-slot name="footer" class="flex justify-between gap-x-4">
                {{-- <x-button flat label="Close" x-on:click="close" /> --}}
                <x-button flat label="Message" href="{{ route('message', $request->reporter->username) }}"/>

                <div>
                    @if($request->status == App\Enums\Status::ReturnRequestReview)
                        <x-button flat negative wire:loading.attr="disabled" wire:click="declineRequest" spinner="declineRequest" label="Decline Request" />
                        <x-button positive wire:loading.attr="disabled" wire:click="acceptRequest" spinner="acceptRequest" label="Accept Request" />
                    @elseif($request->status == App\Enums\Status::ReturnRequestBuyerShipped)
                        <x-button wire:loading.attr="disabled" wire:click="markAsReceievedRequest" spinner="markAsReceievedRequest" label="Mark as Received" />
                        @elseif($request->status == App\Enums\Status::ReturnRequestReceieved)
                        <x-button wire:loading.attr="disabled" wire:click="$dispatch('return-create-order-data', { orderId: {{ $request->order_id }}, requestId: {{ $request->id }} })" onclick="$openModal('returnCreateOrderModal')" label="Create Order" />
                    @elseif($request->status == App\Enums\Status::Accepted)
                        <x-button disabled label="Waiting for buyer's shipment" />
                    @endif
                </div>
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