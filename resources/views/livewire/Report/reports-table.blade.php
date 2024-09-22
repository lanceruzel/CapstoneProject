<div class="bg-white shadow rounded-lg p-5">
    <div class="flex items-center justify-between">
        <p class="font-semibold text-xl">Report lists</p>

        <div class="w-60 flex items-center justify-center gap-3">
            <x-input icon="magnifying-glass" wire:model.live.debounce.200ms="search" placeholder="Search" shadowless />
        </div>
    </div>

    <div class="w-full pt-5 overflow-auto flex items-center justify-center flex-col">
        <table class="table-auto w-full border-spacing-y-4 text-sm text-left">
            <thead class="border-b-2">
                <tr>
                    <th scope="col" class="px-6 py-3">Reporter</th>
                    <th scope="col" class="px-6 py-3">Store</th>
                    <th scope="col" class="px-6 py-3">Order ID</th>
                    <th scope="col" class="px-6 py-3">Reported At</th>
                    <th scope="col" class="px-6 py-3"></th>
                </tr>
            </thead>

            <tbody>
                @if($reports && count($reports) > 0)
                    @foreach ($reports as $report)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-100">
                            <td class="px-6 py-4">{{ $report->reporter->name() }}</td>
                            <td class="px-6 py-4">{{ $report->seller->name() }}</td>
                            <td class="px-6 py-4">#{{ $report->order_id }}</td>
                            <td class="px-6 py-4">{{ $report->created_at }}</td>

                            <td class="-mr-1 px-6 py-4">
                                <x-button label="View Report" icon="eye" flat interaction:solid="info" x-on:click="$openModal('viewReportModal')" wire:click="$dispatch('viewReport', { id: {{ $report->id }} })"/>
                            </td>
                        </tr>
                    @endforeach 
                @endif
            </tbody>
        </table>

        @if(count($reports) <= 0)
            <div class="flex flex-col items-center justify-center mt-5">
                <h1 class="text-2xl font-semibold">No records found</h1>
                <img class="h-[400px]" src="{{ asset('assets/svg/no-data-2.svg') }}" alt="No data found"/>
            </div>
        @endif
    </div>

    <!-- Pagination -->
    <div class="w-full mt-5">
        {{ $reports->links() }}
    </div>
</div>