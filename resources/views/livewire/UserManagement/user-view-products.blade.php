<x-modal-card name="viewProductsModal" title="User Products" width='2xl' persistent align='center' x-cloak x-on:close="$dispatch('clearViewProductsModal')" blurless wire:ignore.self>  
    @if($products)
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
                            <th scope="col" class="px-6 py-3">Name</th>
                            <th scope="col" class="px-6 py-3">Status</th>
                            <th scope="col" class="px-6 py-3">Total Stocks</th>
                            <th scope="col" class="px-6 py-3">Price Range</th>
                            <th scope="col" class="px-6 py-3"></th>
                        </tr>
                    </thead>

                    <tbody>
                        @if($products && $products->count() > 0)
                            @foreach ($products as $product)
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-100">
                                    <td class="px-6 py-4">{{ $product->name }}</td>
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
                                        <x-button flat label="View" onclick="$openModal('productViewModal')" wire:click="$dispatch('view-product-info', { id: {{ $product->id }} })"  />
                                    </td>
                                </tr>
                            @endforeach 
                        @endif
                    </tbody>
                </table>
        
                <!-- Pagination -->
                <div class="w-full mt-5">
                    {{ $products->links() }}
                </div>

                @if($products->count() <= 0)
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