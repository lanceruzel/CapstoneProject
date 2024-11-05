<x-modal-card name="viewAffiliates" title="Affiliates" width='5xl' persistent align='center' x-cloak x-on:close="$dispatch('clearViewAffiliatesModal')" blurless wire:ignore.self>  
    @if($affiliates)
        <div class="flex flex-col gap-2 items-start text-gray-600">
            <div class="flex items-center justify-between w-full">
                <p class="font-semibold text-xl">Affiliate lists</p>
            </div>
        
            <div class="w-full pt-5 overflow-auto flex items-center justify-center flex-col">
                <table class="table-auto w-full border-spacing-y-4 text-sm text-left">
                    <thead class="border-b-2">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-center">
                                @if($mode == 'store')
                                    <span>Promoter</span>
                                @else
                                    <span>Store</span>
                                @endif
                            </th>
                            <th scope="col" class="px-6 py-3 text-center">Affiliate Code</th>
                            <th scope="col" class="px-6 py-3 text-center">Discount %</th>
                            <th scope="col" class="px-6 py-3 text-center">Commission % Per Order</th>
                            <th scope="col" class="px-6 py-3 text-center">Total</th>
                            <th scope="col" class="px-6 py-3 text-center">Unclaimed</th>
                            <th scope="col" class="px-6 py-3 text-center">Status</th>
                            <th scope="col" class="px-6 py-3 text-center"></th>
                        </tr>
                    </thead>

                    <tbody>
                        @if($affiliates && $affiliates->count() > 0)
                            @foreach ($affiliates as $affiliate)
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-100">
                                    <td class="px-6 py-4 text-center">
                                        @if($mode == 'store')
                                            {{ $affiliate->user->userInformation->fullname() }}
                                        @else
                                            {{ $affiliate->store->storeInformation->name }}
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">{{ $affiliate->affiliate_code }}</td>
                                    <td class="px-6 py-4 text-center">{{ $affiliate->discount }}%</td>
                                    <td class="px-6 py-4 text-center">{{ $affiliate->rate }}%</td>
                                    <td class="px-6 py-4 text-center">${{ number_format($affiliate->total, 2) }}</td>
                                    <td class="px-6 py-4 text-center">${{ number_format($affiliate->unclaimed, 2) }}</td>
                                    <td class="px-6 py-4 text-center">
                                        @if($affiliate->status == App\Enums\Status::Active)
                                            <x-badge flat positive label="Active" />
                                        @elseif($affiliate->status == App\Enums\Status::Inactive)
                                            <x-badge flat negative label="Inactive" />
                                        @else
                                            <x-badge flat warning label="{{ $affiliate->status }}" />
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <x-button flat label="Payout History" x-on:click="$openModal('payoutHistoryModal')" wire:click="$dispatch('viewPayout', { promoter: {{ $affiliate->promoter_id }}, store: {{ $affiliate->store_id }} })"/>
                                    </td>
                                </tr>
                            @endforeach 
                        @endif
                    </tbody>
                </table>
        
                {{-- <!-- Pagination -->
                <div class="w-full mt-5">
                    {{ $affiliates->links() }}
                </div> --}}

                @if($affiliates->count() <= 0)
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