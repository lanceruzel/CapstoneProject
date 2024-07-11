<div class="bg-white shadow rounded-lg p-5">
    <div class="flex items-center justify-between">
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
                    <th scope="col" class="px-6 py-3">Product</th>
                    <th scope="col" class="px-6 py-3">Status</th>
                    <th scope="col" class="px-6 py-3">Total Stocks</th>
                    <th scope="col" class="px-6 py-3">Price Range</th>
                    <th scope="col" class="px-6 py-3"></th>
                </tr>
            </thead>

            <tbody>
                @if($products && count($products) > 0)
                    @foreach ($products as $product)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-100">
                            <td class="px-6 py-4 flex items-center justify-start gap-3">
                                <div class="rounded-lg w-16 h-16 border" wire:ignore>
                                    <img src="{{ asset('uploads/products') . '/' . json_decode($product->images)[0] }}" class="w-full h-full object-cover object-center rounded-lg" alt="...">
                                </div>

                                <div>
                                    {{ $product->name }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if($product->status == App\Enums\Status::ForReview)
                                    <x-badge flat info label="For Review" />
                                @elseif($product->status == App\Enums\Status::Available)
                                    <x-badge flat positive label="Available" />
                                @elseif($product->status == App\Enums\Status::Unavailable)
                                    <x-badge flat negative label="Unavailable" />
                                @elseif($product->status == App\Enums\Status::ForReSubmission)
                                    <x-badge flat warning label="For Resumission" />
                                @elseif($product->status == App\Enums\Status::Suspended)
                                    <x-badge flat negative label="Suspended" />
                                @else
                                    {{ $product->status }}
                                @endif
                            </td>
                            <td class="px-6 py-4">x{{ $product->totalStocks() }}</td>
                            <td class="px-6 py-4">{{ $product->priceRange() }}</td>
                            <td class="px-6 py-4">
                                @if($product->status == App\Enums\Status::Suspended)
                                    @if($product->appeal)
                                        <x-button label="View Appeal" onclick="$openModal('reportAppealConversationModal')" wire:click="$dispatch('view-appeal-convo', { id: {{ $product->appeal->conversation_id }} })" />
                                    @else
                                        <x-button label="Make an Appeal" onclick="$openModal('productAppealFormModal')" wire:click="$dispatch('for-product-appeal', { id: {{ $product->id }} })" />
                                    @endif
                                @else
                                    <x-dropdown icon="bars-3">
                                        <x-dropdown.item label="Add Stock" onclick="$openModal('addStockFormModal')" wire:click="$dispatch('viewProductStocksInformation', { id: {{ $product->id }} })" />
                                        <x-dropdown.item label="Update Product" onclick="$openModal('productFormModal')" wire:click="$dispatch('viewProductInformation', { id: {{ $product->id }} })" />
                                    </x-dropdown> 
                                @endif
                            </td>
                        </tr>
                    @endforeach 
                @endif
            </tbody>
        </table>

        @if(count($products) <= 0)
            <div class="flex flex-col items-center justify-center mt-5">
                <h1 class="text-2xl font-semibold">No products found</h1>
                <img class="h-[400px]" src="{{ asset('assets/svg/no-data-2.svg') }}" alt="No data found"/>
            </div>
        @endif
    </div>

    <!-- Pagination -->
    <div class="w-full mt-5">
        {{ $products->links() }}
    </div>
</div>