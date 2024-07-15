<div class="bg-white shadow rounded-lg p-5">
    <div class="flex items-center justify-between">
        <p class="font-semibold text-xl">Affiliates List</p>

        <div class="w-60 flex items-center justify-center gap-3">
            <x-dropdown>
                <x-slot name="trigger">
                    <x-mini-button rounded icon="funnel" flat gray interaction="gray" />
                </x-slot>
            
                <x-dropdown.header label="Filter">
                    <x-dropdown.item>
                        <x-checkbox label="Active" wire:model.live="filterStatus" :value="App\Enums\Status::Active" />
                    </x-dropdown.item>

                    <x-dropdown.item>
                        <x-checkbox label="Inactive" wire:model.live="filterStatus" :value="App\Enums\Status::Inactive" />
                    </x-dropdown.item>

                    <x-dropdown.item>
                        <x-checkbox label="Invitation" wire:model.live="filterStatus" :value="App\Enums\Status::Invitation" />
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
                    <th scope="col" class="px-6 py-3">Promoter</th>
                    <th scope="col" class="px-6 py-3">Affiliate Code</th>
                    <th scope="col" class="px-6 py-3">Total Commissioned</th>
                    <th scope="col" class="px-6 py-3">Rate Per Order</th>
                    <th scope="col" class="px-6 py-3">Status</th>
                    <th scope="col" class="px-6 py-3"></th>
                </tr>
            </thead>

            <tbody>
                @if($affiliates && count($affiliates) > 0)
                    @foreach ($affiliates as $affiliate)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-100">
                            <td class="px-6 py-4">{{ $affiliate->user->userInformation->fullName() }}</td>
                            <td class="px-6 py-4">{{ $affiliate->affiliate_code }}</td>
                            <td class="px-6 py-4">${{ number_format($affiliate->totalCommissioned, 2) }}</td>
                            <td class="px-6 py-4">{{ $affiliate->rate }}%</td>
                            <td class="px-6 py-4">
                                @if($affiliate->status == App\Enums\Status::Invitation)
                                    <x-badge flat info label="Invitation" />
                                @elseif($affiliate->status == App\Enums\Status::Active)
                                    <x-badge flat positive label="Active" />
                                @elseif($affiliate->status == App\Enums\Status::Inactive)
                                    <x-badge flat negative label="Inactive" />
                                @elseif($affiliate->status == App\Enums\Status::Declined)
                                    <x-badge flat negative label="Declined" />
                                @else
                                    <x-badge flat warning label="{{ $affiliate->status }}" />
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <x-button label="View" onclick="$openModal('reportAppealFormModal')" wire:click="$dispatch('view-report-appeal-conversation', { id: {{ $affiliate->id }} })" />
                            </td>
                        </tr>
                    @endforeach 
                @endif
            </tbody>
        </table>

        @if(count($affiliates) <= 0)
            <div class="flex flex-col items-center justify-center mt-5">
                <h1 class="text-2xl font-semibold">No products found</h1>
                <img class="h-[400px]" src="{{ asset('assets/svg/no-data-2.svg') }}" alt="No data found"/>
            </div>
        @endif
    </div>

    <!-- Pagination -->
    <div class="w-full mt-5">
        {{ $affiliates->links() }}
    </div>
</div>