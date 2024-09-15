<x-modal-card name="storeRegistrationModal" title="Store Registration" align='center' x-cloak x-on:close="$dispatch('clearStoreRegistrationData')" blurless wire:ignore.self>
    @if($requirements)
        <div class="flex flex-col gap-2 items-start text-gray-600" wire:target='contact'>
            <div class="grid grid-cols-2 gap-3 w-full">
                <h1 class="col-span-2 text-xl font-semibold">Store Information</h1>

                <x-input class="max-lg:col-span-2" disabled label="Store Contact" wire:model="contact" shadowless />
                <x-input class="max-lg:col-span-2" disabled label="Store Email" wire:model="email" shadowless />

                <x-input class="max-lg:col-span-2" disabled label="Country" wire:model="country" shadowless />
                <x-input class="max-lg:col-span-2" disabled label="Address" wire:model="address" shadowless />
            </div>

            <table class="table-auto w-full border-spacing-y-4 text-sm text-left">
                <thead class="border-b-2">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-center">Requirement</th>
                        <th scope="col" class="px-6 py-3 text-center">Link</th>
                        <th scope="col" class="px-6 py-3 text-center">Status</th>
                        <th scope="col" class="px-6 py-3 text-center"></th>
                    </tr>
                </thead>

                <tbody>
                    @foreach(array_slice((array) $requirements, 0, -2) as $key => $requirement)
                        <tr class="border-b">
                            <td class="text-center py-3">
                                @switch($key)
                                    @case('validId')
                                        Valid ID
                                        @break

                                    @case('businessPermit')
                                        Business Permit
                                        @break

                                    @case('registrationDTI')
                                        Certificate of Registration (<span class="font-semibold underline underline-offset-2">DTI</span>)
                                        @break

                                    @case('registrationBIR')
                                        Certificate of Registration (<span class="font-semibold underline underline-offset-2">BIR</span>)
                                        @break
                                
                                    @default
                                @endswitch
                            </td>
                            <td class="text-center">
                                <x-button info label="View" onclick="$openModal('pdfViewModal')" wire:click="$dispatch('view-pdf', { filename: '{{ $requirements->$key->file_path }}' })" sm />
                            </td>
                            <td class="text-center">
                                @if($requirements->$key->status == App\Enums\Status::ForReview)
                                    <x-badge flat info label="For Review" />
                                @elseif($requirements->$key->status == App\Enums\Status::Accepted)
                                    <x-badge flat positive label="Accepted" />
                                @elseif($requirements->$key->status == App\Enums\Status::Declined)
                                    <x-badge flat negative label="Declined" />
                                @endif
                            </td>
                            <td class="flex flex-row items-center justify-center gap-3">
                                @if($requirements->status != App\Enums\Status::Accepted)
                                    <x-mini-button rounded negative icon="x-mark" wire:click="declineDocument('{{ $key }}')" />
                                    <x-mini-button rounded positive icon="check" wire:click="acceptDocument('{{ $key }}')" />
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            @if($requirements->status != App\Enums\Status::Accepted)
                <x-textarea wire:model='remarks' label="Remarks" placeholder="Send remarks" shadowless />
            @endif
            
            <x-slot name="footer" class="flex justify-end gap-x-4">
                <x-button flat label="Cancel" x-on:click="close" />

                @if($requirements->status != App\Enums\Status::Accepted)
                    <x-button wire:loading.attr="disabled" wire:click="updateRegistration" spinner="updateRegistration" label="Update" />
                @endif
            </x-slot>
        </div>
    @else
        <div class="flex items-center justify-center">
            <div class="flex items-row items-center justify-center gap-3">
                <x-icon name='arrow-path' class="h-5 w-5 animate-spin"/>

                <span>
                    Fetching Data...
                </span>
            </div>
        </div>
    @endif
</x-modal-card>