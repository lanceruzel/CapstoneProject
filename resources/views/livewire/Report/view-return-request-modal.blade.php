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
            @elseif($request->status == App\Enums\Status::AdminProductSuspend)
                <x-alert title="Admin has taken an action" info />
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

                            @if($request->status == App\Enums\Status::Declined && ($request->cancel_reason != null || $request->cancel_reason != ''))
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">Decline Reason: </td>
                                    <td class="px-6 py-4">{{ $request->cancel_reason  }}</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                <x-errors only="media" />

                <p class="text-gray-600 mt-3">Media</p>

                @if($media)
                    <div class="max-w-full flex gap-4 overflow-x-auto p-3" uk-lightbox>
                        @foreach($media as $key => $item)
                            <div class="flex-shrink-0 w-56 h-56 relative">
                                @if(App\Classes\FileTypeIdentifier::identify($item) == 'video')
                                    <a data-type="video" class="relative" href="{{ asset('uploads/report') . '/' . $item }}">
                                        <video src="{{ asset('uploads/report') . '/' . $item }}" class="w-full h-full object-cover rounded-lg shadow border" alt="video"></video>

                                        <div class="absolute top-0 bottom-0 right-0 left-0 flex items-center justify-center">
                                            <x-icon name="play-circle" solid class="w-10 h-10" />
                                        </div>
                                    </a>
                                @else
                                    <a href="{{ asset('uploads/report') . '/' . $item }}">
                                        <img src="{{ asset('uploads/report') . '/' . $item }}" class="w-full h-full object-cover rounded-lg shadow border" alt="image">
                                    </a>
                                @endif
                            </div>  
                        @endforeach
                    </div>
                @endif 
            </div>
            
            <x-slot name="footer" class="flex justify-between gap-x-4">
                {{-- <x-button flat label="Close" x-on:click="close" /> --}}
                <x-button flat label="Message" href="{{ route('message', $request->reporter->username) }}"/>

                <div>
                    @if($request->AdminProductSuspend != App\Enums\Status::ReturnRequestReview)
                        @if($request->status == App\Enums\Status::ReturnRequestReview)
                            <x-button outline negative wire:loading.attr="disabled" label="Decline Request" onclick="$openModal('returnReuqestCancellationModal')" wire:click="$dispatch('cancellationRequest', { id: {{ $request->id }} })" />
                            <x-button positive wire:loading.attr="disabled" wire:click="acceptRequest" spinner="acceptRequest" label="Approve Request" />
                        @elseif($request->status == App\Enums\Status::ReturnRequestBuyerShipped)
                            <x-button wire:loading.attr="disabled" wire:click="markAsReceievedRequest" spinner="markAsReceievedRequest" label="Mark as Received" />
                            @elseif($request->status == App\Enums\Status::ReturnRequestReceieved)
                            <x-button wire:loading.attr="disabled" wire:click="$dispatch('return-create-order-data', { orderId: {{ $request->order_id }}, requestId: {{ $request->id }} })" onclick="$openModal('returnCreateOrderModal')" label="Return Order" />
                        @elseif($request->status == App\Enums\Status::Accepted)
                            <x-button disabled label="Waiting for buyer's shipment" />
                        @endif
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