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
                        <x-checkbox label="Available" wire:model.live="filterStatus" :value="App\Enums\Status::Available" />
                    </x-dropdown.item>

                    <x-dropdown.item>
                        <x-checkbox label="For Review" wire:model.live="filterStatus" :value="App\Enums\Status::ForReview" />
                    </x-dropdown.item>

                    <x-dropdown.item>
                        <x-checkbox label="For Resubmission" wire:model.live="filterStatus" :value="App\Enums\Status::ForReSubmission" />
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
                    <th scope="col" class="px-6 py-3">Store</th>
                    <th scope="col" class="px-6 py-3">Product</th>
                    <th scope="col" class="px-6 py-3">Status</th>
                    <th scope="col" class="px-6 py-3">Remarks</th>
                    <th scope="col" class="px-6 py-3">Date Submitted</th>
                    <th scope="col" class="px-6 py-3"></th>
                </tr>
            </thead>

            <tbody>
                @if($products && count($products) > 0)
                    @foreach ($products as $product)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-100">
                            <td class="px-6 py-4">
                                {{ $product->seller->role == App\Enums\UserType::Travelpreneur ? $product->seller->userInformation->fullname() : $product->seller->storeInformation->name }}
                            </td>
                            <td class="px-6 py-4">{{ $product->name }}</td>

                            <td class="px-6 py-4">
                                @if($product->status == App\Enums\Status::ForReview)
                                    <x-badge flat info label="For Review" />
                                @elseif($product->status == App\Enums\Status::Available)
                                    <x-badge flat positive label="Available" />
                                @elseif($product->status == App\Enums\Status::ForReSubmission)
                                    <x-badge flat warning label="For Resubmission" />
                                @else
                                    {{ $product->status }}
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($product->remarks)
                                    {{$product->remarks}}
                                @else
                                    <x-badge flat info label="No Remarks Available" />
                                @endif
                            </td>

                            <td class="px-6 py-4">{{ date_format($product->created_at, "M d, Y") }}</td>

                            <td class="-mr-1 px-6 py-4">
                                <x-button label="View Product" icon="eye" flat interaction:solid="info" x-on:click="$openModal('productRegistrationModal')" wire:click="$dispatch('viewProductRegistration', { id: {{ $product->id }} })"/>
                            </td>
                        </tr>
                    @endforeach 
                @endif
            </tbody>
        </table>

        @if(count($products) <= 0)
            <div class="flex flex-col items-center justify-center mt-5">
                <h1 class="text-2xl font-semibold">No records found</h1>
                <img class="h-[400px]" src="{{ asset('assets/svg/no-data-2.svg') }}" alt="No data found"/>
            </div>
        @endif
    </div>

    <!-- Pagination -->
    <div class="w-full mt-5">
        {{ $products->links() }}
    </div>
</div>