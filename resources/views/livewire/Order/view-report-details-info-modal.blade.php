<x-modal-card name="viewReportDetailsModal" title="Report Details" align='center' x-cloak x-on:close="$dispatch('clearViewReportDetailsModal')" blurless wire:ignore.self>  
    @if($report)
        <div class="flex flex-col gap-2 items-start text-gray-600 overflow-auto">
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
                                    Status
                                </th>
                                <td class="px-6 py-4">
                                    @if($report->status == App\Enums\Status::Accepted)
                                        <span>Seller has accepted your return request. </span>
                                    @elseif($report->status == App\Enums\Status::ReturnRequestBuyerShipped)
                                        <span>You have shipped the item and is now waiting for seller to received. </span>
                                    @elseif($report->status == App\Enums\Status::ReturnRequestReceieved)
                                        <span>Seller has received the item and now preparing to send you your item/s. </span>
                                    @elseif($report->status == App\Enums\Status::ReturnRequestSellerOrderCreated)
                                        <span>Request has been fulfilled. </span>
                                    @elseif($report->status == App\Enums\Status::ReturnRequestReview)
                                        <span>Your request is for review. </span>
                                    @elseif($report->status == App\Enums\Status::Declined)
                                        <span>Your request has been declined. </span>
                                    @else
                                        {{ $report->status }}
                                    @endif
                                </td>
                            </tr>

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
            
            <x-slot name="footer" class="flex justify-end gap-x-4">
                <x-button flat label="Close" x-on:click="close" />
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