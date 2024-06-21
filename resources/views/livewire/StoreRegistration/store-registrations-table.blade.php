<div class="bg-white shadow rounded-lg p-5">
    <div class="flex items-center justify-between">
        <p class="font-semibold text-xl">Store lists</p>

        <div class="w-60">
            <x-input icon="magnifying-glass" wire:model.live.debounce.200ms="search" placeholder="Search" shadowless />
        </div>
    </div>

    <div class="w-full pt-5 overflow-auto">
        <table class="table-auto w-full border-spacing-y-4 text-sm text-left">
            <thead class="border-b-2">
                <tr>
                    <th scope="col" class="px-6 py-3">Store</th>
                    <th scope="col" class="px-6 py-3">Status</th>
                    <th scope="col" class="px-6 py-3">Last Validator</th>
                    <th scope="col" class="px-6 py-3">Remarks</th>
                    <th scope="col" class="px-6 py-3">Last Update</th>
                    <th scope="col" class="px-6 py-3">Date Submitted</th>
                    <th scope="col" class="px-6 py-3"></th>
                </tr>
            </thead>

            <tbody>
                {{-- @if(count($registrations) > 0)
                    @foreach ($registrations as $registration)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-100">
                            <td class="px-6 py-4">{{ $registration->user->role === UserType::Travelpreneur ? $registration->user->userInfo->first_name . ' ' . $registration->user->userInfo->last_name . '\'s store' : $registration->user->storeInfo->name }}</td>
                            <td class="px-6 py-4">
                                @if($registration->status == 'Denied')
                                    {{ 'Waiting for resubmission' }}
                                @elseif($registration->status == BusReqStatus::Resubmitted)
                                    {{ 'Waiting for re-validation' }}
                                @elseif($registration->status == BusReqStatus::Validated)
                                    {{ 'Validated' }}
                                @else
                                    {{ 'Waiting for validation' }}
                                @endif
                            </td>

                            <td class="px-6 py-4">
                                @if ($registration->validated_by)
                                    {{ $registration->validatedBy->userInfo->first_name . ' ' . $registration->validatedBy->userInfo->last_name }}
                                @else
                                    None
                                @endif
                            </td>

                            <td class="px-6 py-4">{{ $registration->remarks }}</td>
                            <td class="px-6 py-4">{{ date_format($registration->updated_at, "M d, Y g:i a") }}</td>
                            <td class="px-6 py-4">{{ date_format($registration->created_at, "M d, Y g:i a") }}</td>

                            <td class="-mr-1 px-6 py-4">
                                @if($registration->status != 'Validated')
                                    <x-button-with-icon label="Validate" icon='<i class="ri-edit-2-line ri-lg"></i>' wire:click="$dispatch('for-validation', { id: {{ $registration->id }} })" uk-toggle="target: #validation-modal"/>
                                @else
                                    <button class='bg-green-500 text-white py-2 px-5 text-sm font-medium rounded-lg active:scale-95 transition-all'>
                                        <span>Validated</span>
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @endforeach 
                @else --}}
                    <tr>
                        <td colspan="7" class="text-center px-6 py-4 bg-gray-50">
                            No registrations requests found.
                        </td>
                    </tr>
                {{-- @endif --}}
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="w-full mt-5">
        {{-- {{ $registrations->links() }} --}}
    </div>
</div>