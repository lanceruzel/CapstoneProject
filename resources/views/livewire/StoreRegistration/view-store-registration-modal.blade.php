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
                    
                        <tr class="border-b">
                            <td class="text-center py-3">Requirement 1</td>
                            <td class="text-center">
                                <x-button info label="View" sm />
                            </td>
                            <td class="text-center">
                                @if($requirements->requirement_1->status == App\Enums\Status::ForReview)
                                    <x-badge flat info label="For Review" />
                                @else
                                    {{ $requirements->requirement_1->status }}
                                @endif
                            </td>
                            <td class="flex flex-row items-center justify-center gap-3">
                                <x-mini-button rounded negative icon="x-mark" />
                                <x-mini-button rounded positive icon="check" />
                            </td>
                        </tr>

                        <tr class="border-b">
                            <td class="text-center py-3">Requirement 2</td>
                            <td class="text-center">
                                <x-button info label="View" sm />
                            </td>
                            <td class="text-center">
                                @if($requirements->requirement_2->status == App\Enums\Status::ForReview)
                                    <x-badge flat info label="For Review" />
                                @else
                                    {{ $requirements->requirement_2->status }}
                                @endif
                            </td>
                            <td class="flex flex-row items-center justify-center gap-3">
                                <x-mini-button rounded negative icon="x-mark" />
                                <x-mini-button rounded positive icon="check" />
                            </td>
                        </tr>

                        <tr class="border-b">
                            <td class="text-center py-3">Requirement 3</td>
                            <td class="text-center">
                                <x-button info label="View" sm />
                            </td>
                            <td class="text-center">
                                @if($requirements->requirement_3->status == App\Enums\Status::ForReview)
                                    <x-badge flat info label="For Review" />
                                @else
                                    {{ $requirements->requirement_3->status }}
                                @endif
                            </td>
                            <td class="flex flex-row items-center justify-center gap-3">
                                <x-mini-button rounded negative icon="x-mark" />
                                <x-mini-button rounded positive icon="check" />
                            </td>
                        </tr>
                    
                </tbody>
            </table>

            <x-textarea label="Remarks" placeholder="Send remarks" shadowless />
            
            <x-slot name="footer" class="flex justify-end gap-x-4">
                <x-button flat label="Cancel" x-on:click="close" />
                <x-button wire:loading.attr="disabled" wire:click="store" spinner="store" label="Update" />
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