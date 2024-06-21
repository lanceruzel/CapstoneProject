<div class="bg-white shadow rounded-lg p-5">
    <div class="flex items-center justify-between">
        <p class="font-semibold text-xl">Store lists</p>

        <div class="w-60">
            <x-input icon="magnifying-glass" wire:model.live.debounce.200ms="search" placeholder="Search" shadowless />
        </div>
    </div>

    <div class="w-full pt-5 overflow-auto flex items-center justify-center flex-col">
        <table class="table-auto w-full border-spacing-y-4 text-sm text-left">
            <thead class="border-b-2">
                <tr>
                    <th scope="col" class="px-6 py-3">Store</th>
                    <th scope="col" class="px-6 py-3">Status</th>
                    <th scope="col" class="px-6 py-3">Last Update</th>
                    <th scope="col" class="px-6 py-3">Date Submitted</th>
                    <th scope="col" class="px-6 py-3"></th>
                </tr>
            </thead>

            <tbody>
                @if(count($registrations) > 0)
                    @foreach ($registrations as $registration)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-100">
                            <td class="px-6 py-4">{{ $registration->user->storeInformation->name }}</td>
                            <td class="px-6 py-4">
                                @php
                                    $status = json_decode($registration->user->storeInformation->requirements)->status;
                                @endphp

                                @if($status == App\Enums\Status::ForReview)
                                    <x-badge flat info label="For Review" />
                                @else
                                    {{ $status }}
                                @endif
                            </td>

                            <td class="px-6 py-4">{{ date_format($registration->updated_at, "M d, Y g:i a") }}</td>
                            <td class="px-6 py-4">{{ date_format($registration->created_at, "M d, Y g:i a") }}</td>

                            <td class="-mr-1 px-6 py-4">
                                <x-button label="View Registration" icon="eye" flat interaction:solid="info" x-on:click="$openModal('storeRegistrationModal')" wire:click="$dispatch('viewRegistration', { id: {{ $registration->id }} })"/>
                            </td>
                        </tr>
                    @endforeach 
                @endif
            </tbody>
        </table>

        @if(count($registrations) <= 0)
            <div class="flex flex-col items-center justify-center mt-5">
                <h1 class="text-2xl font-semibold">No records found</h1>
                <img class="h-[400px]" src="{{ asset('assets/svg/no-data-2.svg') }}" alt="No data found"/>
            </div>
        @endif
    </div>

    <!-- Pagination -->
    <div class="w-full mt-5">
        {{-- {{ $registrations->links() }} --}}
    </div>
</div>