<x-modal-card name="affiliatePayoutHistoryModal" width='3xl' title="Payout History" align='center' x-cloak blurless wire:ignore.self>  
    <div class="flex flex-col gap-2 items-center justify-center text-gray-600 overflow-auto p-2">

        <div>
            <div class="p-5 w-full shadow flex flex-col items-center justify-center rounded-md border border-gray-200">
                <h1 class="text-xl font-semibold">Total Payout</h1>
                <h1>${{ number_format(auth()->user()->totalPayouts(), 2) }}</h1>
            </div>
        </div>

        <div class="w-full pt-5 overflow-auto">
            <table class="table-auto w-full border-spacing-y-4 text-sm text-left">
                <thead class="border-b-2">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-center">Store</th>
                        <th scope="col" class="px-6 py-3 text-center">Amount</th>
                        <th scope="col" class="px-6 py-3 text-center">Reference ID</th>
                        <th scope="col" class="px-6 py-3 text-center">Status</th>
                        <th scope="col" class="px-6 py-3 text-center">Date Requested</th>
                    </tr>
                </thead>
    
                <tbody>
                    @if(count($payouts) > 0)
                        @foreach ($payouts as $payout)
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-100">
                                <td class="px-6 py-4 text-center">{{ $payout->requestedTo->storeInformation->name }}</td>
                                <td class="px-6 py-4 text-center">${{ $payout->amount }}</td>
                                <td class="px-6 py-4 text-center">{{ $payout->reference_id ? $payout->reference_id : 'None'  }}</td>
                                <td class="px-6 py-4 text-center">
                                    @if($payout->status == App\Enums\Status::PayoutPending)
                                        <x-badge light warning label="Pending" />
                                    @elseif($payout->status == App\Enums\Status::PayoutProcessing)
                                        <x-badge light primary label="Processing" />
                                    @else
                                        <x-badge light positive label="Sent" />
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">{{ date_format($payout->created_at, "M d, Y g:i a") }}</td>
                            </tr>
                        @endforeach 
                    @else
                        <tr>
                            <td colspan="7" class="text-center px-6 py-4 bg-gray-50">
                                No payouts found.
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>

            <!-- Pagination -->
            <div class="w-full mt-5">
                {{ $payouts->links() }}
            </div>
        </div>

        <x-slot name="footer" class="flex justify-end gap-x-4">
            <x-button flat wire:loading.attr="disabled" label="Close" x-on:click="close" />
        </x-slot>
    </div>
</x-modal-card>
