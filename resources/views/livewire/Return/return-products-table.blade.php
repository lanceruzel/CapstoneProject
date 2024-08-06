<div class="bg-white shadow rounded-lg p-5">
    <div class="flex items-center justify-between">
        <p class="font-semibold text-xl">Requests list</p>

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
                        <x-checkbox label="Available" wire:model.live="filterStatus" :value="App\Enums\Status::Available" />
                    </x-dropdown.item>

                    <x-dropdown.item>
                        <x-checkbox label="Unavailable" wire:model.live="filterStatus" :value="App\Enums\Status::Unavailable" />
                    </x-dropdown.item>

                    <x-dropdown.item>
                        <x-checkbox label="Suspended" wire:model.live="filterStatus" :value="App\Enums\Status::Suspended" />
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
                    <th scope="col" class="px-6 py-3">Order ID</th>
                    <th scope="col" class="px-6 py-3">Product</th>
                    <th scope="col" class="px-6 py-3">Requested By</th>
                    <th scope="col" class="px-6 py-3">Status</th>
                    <th scope="col" class="px-6 py-3">Date</th>
                    <th scope="col" class="px-6 py-3"></th>
                </tr>
            </thead>

            <tbody>
                @if($requests && count($requests) > 0)
                    @foreach ($requests as $request)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-100">
                            <td class="px-6 py-4">#{{ $request->order_id }}</td>
                            <td class="px-6 py-4">
                                {!! implode(',<br>', array_map(function($product) { return $product->name; }, json_decode($request->products))) !!}
                            </td>
                            <td class="px-6 py-4">{{ $request->reporter->name() }}</td>
                            <td class="px-6 py-4">
                                @if($request->status == App\Enums\Status::ReturnRequestReview)
                                    <span>Waiting for your review.</span>
                                @elseif($request->status == App\Enums\Status::Accepted)
                                    <span>Waiting for the buyer to ship the product.</span>
                                @elseif($request->status == App\Enums\Status::ReturnRequestBuyerShipped)
                                    <span>Buyer has shipped the item.</span>
                                @elseif($request->status == App\Enums\Status::ReturnRequestReceieved)
                                    <span>You mark this as you received the item/s. </span>
                                @elseif($request->status == App\Enums\Status::ReturnRequestSellerOrderCreated)
                                    <span>Request has been fulfilled. </span>
                                @elseif($request->status == App\Enums\Status::Declined)
                                    <span>Request has been declined. </span>
                                @else
                                    <span>{{ $request->status }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">{{ $request->created_at }}</td>
                            <td class="px-6 py-4">
                                <x-button label="View" onclick="$openModal('viewReturnRequestModal')" wire:click="$dispatch('viewReturnProductInformation', { id: {{ $request->id }} })" />
                            </td>
                        </tr>
                    @endforeach 
                @endif
            </tbody>
        </table>

        @if(count($requests) <= 0)
            <div class="flex flex-col items-center justify-center mt-5">
                <h1 class="text-2xl font-semibold">No requests found</h1>
                <img class="h-[400px]" src="{{ asset('assets/svg/no-data-2.svg') }}" alt="No data found"/>
            </div>
        @endif
    </div>

    <!-- Pagination -->
    <div class="w-full mt-5">
        {{ $requests->links() }}
    </div>
</div>