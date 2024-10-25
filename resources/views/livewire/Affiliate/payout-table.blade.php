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
                        <x-checkbox label="Pending" wire:model.live="filterStatus" :value="App\Enums\Status::PayoutPending" />
                    </x-dropdown.item>

                    <x-dropdown.item>
                        <x-checkbox label="Processing" wire:model.live="filterStatus" :value="App\Enums\Status::PayoutProcessing" />
                    </x-dropdown.item>

                    <x-dropdown.item>
                        <x-checkbox label="Sent" wire:model.live="filterStatus" :value="App\Enums\Status::PayoutSent" />
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
                    <th scope="col" class="px-6 py-3">Affiliate</th>
                    <th scope="col" class="px-6 py-3">Account Name</th>
                    <th scope="col" class="px-6 py-3">Paypal Email</th>
                    <th scope="col" class="px-6 py-3">Amount</th>
                    <th scope="col" class="px-6 py-3">Reference</th>
                    <th scope="col" class="px-6 py-3">Status</th>
                    <th scope="col" class="px-6 py-3">Date Requested</th>
                    <th scope="col" class="px-6 py-3"></th>
                </tr>
            </thead>

            <tbody>
                @if($payouts && count($payouts) > 0)
                    @foreach ($payouts as $payout)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-100">
                            <td class="px-6 py-4">{{ $payout->user->userInformation->fullName() }}</td>
                            <td class="px-6 py-4">{{ $payout->account_name }}</td>
                            <td class="px-6 py-4">{{ $payout->paypal_email }}</td>
                            <td class="px-6 py-4">${{ number_format($payout->amount, 2) }}</td>
                            <td class="px-6 py-4">{{ $payout->reference_id ? $payout->reference_id : 'None' }}</td>
                            <td class="px-6 py-4">
                                @if($payout->status == App\Enums\Status::PayoutPending)
                                    <x-badge flat warning label="Pending" />
                                @elseif($payout->status == App\Enums\Status::PayoutProcessing)
                                    <x-badge flat label="Processing" />  
                                @else
                                    <x-badge flat positive label="Sent" />
                                @endif
                            </td>

                            <td class="px-6 py-4 text-center">{{ date_format($payout->created_at, "M d, Y g:i a") }}</td>

                            <td class="px-6 py-4">
                                <x-button label="View" onclick="$openModal('payoutModal')" wire:click="$dispatch('view-payout-request', { id: {{ $payout->id }}})" />
                            </td>
                        </tr>
                    @endforeach 
                @endif
            </tbody>
        </table>

        @if(count($payouts) <= 0)
            <div class="flex flex-col items-center justify-center mt-5">
                <h1 class="text-2xl font-semibold">No products found</h1>
                <img class="h-[400px]" src="{{ asset('assets/svg/no-data-2.svg') }}" alt="No data found"/>
            </div>
        @endif
    </div>

    <!-- Pagination -->
    <div class="w-full mt-5">
        {{ $payouts->links() }}
    </div>
</div>