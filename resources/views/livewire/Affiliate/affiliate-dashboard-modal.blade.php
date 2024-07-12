<x-modal-card name="affiliateDashboardModal" width='5xl' title="Affiliate Dashboard" align='center' x-cloak x-on:close="$dispatch('clearaffiliateDashboardModalData')" blurless wire:ignore.self>  
    <div class="flex flex-col gap-2 items-center justify-center text-gray-600 overflow-auto">
        <div class="flex items-center justify-between pb-4 w-full">
            <h1 class="text-2xl font-bold">Dashboard</h1>
    
            <x-button label="View Invites" uk-toggle="target: #affiliate-invitation" />
        </div>
    
        <div class="flex flex-col items-center justify-center border border-gray-200 p-7 rounded-lg shadow">
            <h1 class="mb-3 text-xl font-semibold">Commissions</h1>
    
            <div class="w-full flex items-center justify-center gap-5">
                <div class="p-5 w-fit min-w-48 h-32 shadow flex flex-col items-center justify-center rounded-md border border-gray-200">
                    <h1 class="text-xl font-semibold">Total</h1>
                    <h1>{{ number_format($overallCommissioned, 2); }}</h1>
                </div>
        
                <div class="p-5 w-fit min-w-48 h-32 shadow flex flex-col items-center justify-center rounded-md border border-gray-200">
                    <h1 class="text-xl font-semibold">Today</h1>
                    <h1>{{ number_format($todayCommissioned, 2); }}</h1>
                </div>
        
                <div class="p-5 w-fit min-w-48 h-32 shadow flex flex-col items-center justify-center rounded-md border border-gray-200">
                    <h1 class="text-xl font-semibold">Last Week</h1>
                    <h1>{{ number_format($lastWeekCommissioned, 2); }}</h1>
                </div>
        
                <div class="p-5 w-fit min-w-48 h-32 shadow flex flex-col items-center justify-center rounded-md border border-gray-200">
                    <h1 class="text-xl font-semibold">This month</h1>
                    <h1>{{ number_format($thisMonthCommissioned, 2); }}</h1>
                </div>
            </div>
        </div>
    
        <div class="w-full pt-5 overflow-auto">
            <table class="table-auto w-full border-spacing-y-4 text-sm text-left">
                <thead class="border-b-2">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-center">Store</th>
                        <th scope="col" class="px-6 py-3 text-center">Commission Rate Per Order</th>
                        <th scope="col" class="px-6 py-3 text-center">Total Commissioned</th>
                        <th scope="col" class="px-6 py-3 text-center">Affiliate Code</th>
                        <th scope="col" class="px-6 py-3 text-center">Status</th>
                        <th scope="col" class="px-6 py-3 text-center"></th>
                    </tr>
                </thead>
    
                <tbody>
                    @if(count($affiliates) > 0)
                        @foreach ($affiliates as $affiliate)
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-100">
                                <td class="px-6 py-4 text-center">PC HUB 1202</td>
                                <td class="px-6 py-4 text-center">5%</td>
                                <td class="px-6 py-4 text-center">$100.00</td>
                                <td class="px-6 py-4 text-center">ABCDEF</td>
                                <td class="px-6 py-4 text-center">Active</td>
    
                                <td class="px-6 py-4 flex items-center justify-center">
                                    <x-button label="Copy Code"/>
                                </td>
                            </tr>
                        @endforeach 
                    @else
                        <tr>
                            <td colspan="7" class="text-center px-6 py-4 bg-gray-50">
                                No affiliate found.
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
            
        <x-slot name="footer" class="flex justify-end gap-x-4">
            {{-- <x-button flat label="Close" x-on:click="close" /> --}}
        </x-slot>
    </div>
</x-modal-card>
