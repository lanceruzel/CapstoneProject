<div class="bg-white shadow rounded-lg p-5">
    <div class="flex items-center justify-between">
        <p class="font-semibold text-xl">Report Appeal lists</p>

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
                    <th scope="col" class="px-6 py-3">Seller</th>
                    <th scope="col" class="px-6 py-3">Email</th>
                    <th scope="col" class="px-6 py-3">Date</th>
                    <th scope="col" class="px-6 py-3"></th>
                </tr>
            </thead>

            <tbody>
                @if($appeals && count($appeals) > 0)
                    @foreach ($appeals as $appeal)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-100">
                            <td class="px-6 py-4">{{ $appeal->product->name }}</td>
                            <td class="px-6 py-4">{{ $appeal->product->seller->storeInformation->name }}</td>
                            <td class="px-6 py-4">{{ $appeal->product->seller->email}}</td>
                            <td class="px-6 py-4">{{ date_format($appeal->created_at, "M d, Y") }}</td>
                            <td class="px-6 py-4">
                                <x-button label="View Conversation" onclick="$openModal('reportAppealFormModal')" wire:click="$dispatch('view-report-appeal-conversation', { id: {{ $appeal->id }} })" />
                            </td>
                        </tr>
                    @endforeach 
                @endif
            </tbody>
        </table>

        @if(count($appeals) <= 0)
            <div class="flex flex-col items-center justify-center mt-5">
                <h1 class="text-2xl font-semibold">No products found</h1>
                <img class="h-[400px]" src="{{ asset('assets/svg/no-data-2.svg') }}" alt="No data found"/>
            </div>
        @endif
    </div>

    <!-- Pagination -->
    <div class="w-full mt-5">
        {{ $appeals->links() }}
    </div>
</div>