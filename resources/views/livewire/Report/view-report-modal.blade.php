<x-modal-card name="viewReportModal" title="Report Details" align='center' x-cloak x-on:close="$dispatch('clearViewReportModalData')" blurless wire:ignore.self>  
    @if($report)
        <div class="flex flex-col gap-2 items-start text-gray-600 overflow-auto">
            @if($report->status == App\Enums\Status::ReturnRequestReview)
                <x-alert title="Waiting for the store owner's review" info />
            @elseif($report->status == App\Enums\Status::ReturnRequestBuyerShipped)
                <x-alert title="Item has been shipped by the seller" info />
            @elseif($report->status == App\Enums\Status::Accepted)
                <x-alert title="Seller have accepted this request and is now waiting for the buyer to ship the item/s" info />
            @elseif($report->status == App\Enums\Status::ReturnRequestReceieved)
                <x-alert title="Buyer mark this as received." info />
            @elseif($report->status == App\Enums\Status::ReturnRequestSellerOrderCreated)
                <x-alert title="This request has been fulfilled." info />
            @elseif($report->status == App\Enums\Status::Declined)
                <x-alert title="Buyer declined this request." info />
            @elseif($report->status == App\Enums\Status::AdminProductSuspend)
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
                                    Reporter
                                </th>
                                <td class="px-6 py-4">
                                    {{ $report->reporter->name() }}
                                </td>
                            </tr>

                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    Seller
                                </th>
                                <td class="px-6 py-4">
                                    {{ $report->seller->name() }}
                                </td>
                            </tr>

                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    Order ID
                                </th>
                                <td class="px-6 py-4">
                                    #{{ $report->order_id }}
                                </td>
                            </tr>

                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    Product
                                </th>
                                <td class="px-6 py-4">
                                    @foreach(json_decode($report->products) as $items)
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
                                    {{ ucfirst($report->reason) }}
                                </td>
                            </tr>

                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    Description
                                </th>
                                <td class="px-6 py-4">
                                    {{ $description }}
                                </td>
                            </tr>

                            @if($report->status == App\Enums\Status::Declined && ($report->cancel_reason != null || $report->cancel_reason != ''))
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">Decline Reason: </td>
                                    <td class="px-6 py-4">{{ $report->cancel_reason  }}</td>
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
                <x-button flat wire:loading.attr="disabled" wire:click="exportReport" spinner="exportReport" label="Export" />

                <div>
                    <x-button wire:loading.attr="disabled" flat label="Close" x-on:click="close" />

                    @if($report->status != App\Enums\Status::AdminProductSuspend)
                        <x-button negative wire:loading.attr="disabled" wire:click="confirmSuspend" spinner="suspendProducts" label="Suspend Product/s" />
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