<x-modal-card name="payoutModal" width='lg' title="Payout Request" align='center' x-cloak x-on:close="$dispatch('clearPayoutModalData')" blurless wire:ignore.self>  
    @if($payout)
        <div class="flex flex-col gap-2 items-start text-gray-600 overflow-auto w-full">

            <div class="relative overflow-x-auto w-full">
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
                                Affiliate
                            </th>

                            <td class="px-6 py-4">
                                {{ $payout->user->name() }}
                            </td>
                        </tr>

                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                Account Name
                            </th>

                            <td class="px-6 py-4">
                                {{ $payout->account_name }}
                            </td>
                        </tr>

                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                Paypal Email
                            </th>

                            <td class="px-6 py-4">
                                {{ $payout->paypal_email }}
                            </td>
                        </tr>

                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                Amount
                            </th>

                            <td class="px-6 py-4">
                                ${{ number_format($payout->amount, 2) }}
                            </td>
                        </tr>

                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                Reference
                            </th>

                            <td class="px-6 py-4">
                                @if($payout->reference_id)
                                    {{ $payout->reference_id }}
                                @else
                                    <x-input label="" wire:model="reference" placeholder="Add reference after you send the money" shadowless />
                                @endif
                            </td>
                        </tr>

                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                Status
                            </th>

                            <td class="px-6 py-4">
                                @if($payout->status == App\Enums\Status::PayoutPending)
                                    <span>Pending</span>
                                @elseif($payout->status == App\Enums\Status::PayoutProcessing)
                                    <span>Processing</span> 
                                @else
                                    <span>Sent</span>
                                @endif
                            </td>
                        </tr>

                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                Date Requested
                            </th>

                            <td class="px-6 py-4">
                                {{ date_format($payout->created_at, "M d, Y") }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <x-slot name="footer" class="flex justify-end gap-x-4">
                <x-button flat wire:loading.attr="disabled" label="Close" x-on:click="close" />

                @if(!$payout->reference_id)
                    <x-button wire:loading.attr="disabled" wire:click="confirmUpdate" spinner="update" label="Update" />
                @endif
            </x-slot>
        </div>
    @else
        <div class="flex items-center justify-center w-full">
            <div class="flex items-row items-center justify-center gap-3">
                <x-icon name='arrow-path' class="h-5 w-5 animate-spin"/>

                <span>
                    Fetching data...
                </span>
            </div>
        </div>
    @endif
 </x-modal-card>