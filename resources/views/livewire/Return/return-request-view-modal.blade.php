<x-modal-card name="viewReturnRequestModal" title="View Request Information" align='center' x-cloak x-on:close="$dispatch('clearViewReturnRequestModalData')" blurless wire:ignore.self>  
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
            @else
                <x-alert title="{{ $request->status }}" info />
            @endif

            <table class="border border-gray-600 table-auto w-full border-spacing-y-4 text-left">
                <tbody class="w-full">
                    <tr>
                        <td class="p-2 font-medium border border-gray-300">Order Number: #</td>
                        <td class="p-2 border border-gray-300">{{ $request->order_id }}</td>
                    </tr>

                    <tr>
                        <td class="p-2 font-medium border border-gray-300">Products: </td>
                        <td class="p-2 border border-gray-300">{{ implode(', ', array_map(function($product) { return $product->name; }, json_decode($request->products))) }}</td>
                    </tr>

                    <tr>
                        <td class="p-2 font-medium border border-gray-300">Requested By: </td>
                        <td class="p-2 border border-gray-300">{{ $request->reporter->name() }}</td>
                    </tr>

                    <tr>
                        <td class="p-2 font-medium border border-gray-300">Requested By: </td>
                        <td class="p-2 border border-gray-300">{{ $request->created_at }}</td>
                    </tr>

                    <tr>
                        <td class="p-2 font-medium border border-gray-300">Reason: </td>
                        <td class="p-2 border border-gray-300">{{ $request->content  }}</td>
                    </tr>

                    @if($request->tracking_number != null && $request->courrier)
                        <tr>
                            <td class="p-2 font-medium border border-gray-300">Courrier: </td>
                            <td class="p-2 border border-gray-300">{{ $request->courrier  }}</td>
                        </tr>

                        <tr>
                            <td class="p-2 font-medium border border-gray-300">Tracking Number: </td>
                            <td class="p-2 border border-gray-300">{{ $request->tracking_number  }} <x-link label="View Tracking" href="https://parcelsapp.com/en/tracking/{{ $request->tracking_number }}" target="_blank" /></td>
                        </tr>
                    @endif
                </tbody>
            </table>

            <div class="w-full">
                <p class="font-medium">Images Provided: </p>
                <div class="w-full mt-3" wire:ignore>
                    @if($images != null)
                        @if(count($images) === 1)
                            <!-- post image -->
                            <div class="relative w-full h-full" uk-lightbox>
                                <a href="{{ asset('uploads/return') . '/' . $images[0] }}">
                                    <img src="{{ asset('uploads/return') . '/' . $images[0] }}" alt="" class="sm:rounded-lg w-full h-full object-cover">
                                </a>
                            </div>
                        @elseif(count($images) > 1)
                            <!-- slide images -->
                            <div class="relative uk-visible-toggle uk-slideshow w-full" tabindex="-1" uk-slideshow="animation: push;finite: true;min-height: 300; max-height: 350">
    
                                <ul class="uk-slideshow-items" uk-lightbox="" style="min-height: 350px;">
                                    @foreach($images as $image)
                                        <li class="w-full sm:rounded-md" tabindex="-1" style="">
                                            <a href="{{ asset('uploads/return') . '/' . $image }}">
                                                <img src="{{ asset('uploads/return') . '/' . $image }}" class="w-full h-full object-cover inset-0" alt="">
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
    
                                <!-- navigation -->
                                <button type="button" class="absolute -left-3 -translate-y-1/2 bg-gray-100/50 backdrop-blur-xl rounded-full top-1/2 grid w-8 h-7 place-items-center border" uk-slideshow-item="previous">
                                    <x-icon name="chevron-left" class="w-5 h-5" />
                                </button>
    
                                <button type="button" class="absolute -right-3 -translate-y-1/2 bg-gray-100/50 backdrop-blur-xl rounded-full top-1/2 grid w-8 h-7 place-items-center border uk-invisible" uk-slideshow-item="next">
                                    <x-icon name="chevron-right" class="w-5 h-5" />
                                </button>
                            </div>
                        @endif
                    @endif
                </div>
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
                    @endif
                </div>
            </x-slot>
        </div>
    @else
        <div class="flex items-center justify-center w-full">
            <div class="flex items-row items-center justify-center gap-3">
                <x-icon name='arrow-path' class="h-5 w-5 animate-spin"/>

                <span>
                    Fetching data...
                </span>
            </div>
        </div>
    @endif
 </x-modal-card>