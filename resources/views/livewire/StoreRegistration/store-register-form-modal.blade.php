<x-modal-card name="storeRegistrationFormModal" title="Store Registration" align='center' x-cloak x-on:close="$dispatch('clearstoreRegistrationData')" blurless wire:ignore.self>
    @if($registrationStatus == App\Enums\Status::ForSubmission)
        <div class="flex flex-col gap-2 items-start text-gray-600">
            <div class="grid grid-cols-2 gap-3 w-full">
                <h1 class="col-span-2 text-xl font-semibold">Store Information <span class="text-sm text-gray-500">(Note: The following information will be used to contact your store)</span></h1>

                <x-input class="max-lg:col-span-2" label="Store Contact" wire:model="contact" shadowless />
                <x-input class="max-lg:col-span-2" label="Store Email" wire:model="email" shadowless />

                <x-select class="max-lg:col-span-2" label="Country" wire:model="country" placeholder="Select Country" :options="$countries" searchable shadowless />
                <x-input class="max-lg:col-span-2" label="Address" wire:model="address" shadowless />
            </div>

            <div class="grid grid-cols-2 gap-3 w-full">
                <h1 class="col-span-2 text-xl font-semibold">Store Requirements</h1>

                <x-input class="col-span-2" type="file" label="Requirement 1" wire:model="requirement_1" shadowless>
                    <x-slot name='corner' wire:target='requirement_1' wire:loading>
                        <div class="flex items-center justify-center gap-2">
                            <span>
                                <x-icon name="arrow-path" class="w-5 h-5 animate-spin" />
                            </span>
                            
                            <span>
                                Uploading...
                            </span>
                        </div>
                    </x-slot>
                </x-input>

                <x-input class="col-span-2" type="file" label="Requirement 2" wire:model="requirement_2" shadowless>
                    <x-slot name='corner' wire:target='requirement_2' wire:loading>
                        <div class="flex items-center justify-center gap-2">
                            <span>
                                <x-icon name="arrow-path" class="w-5 h-5 animate-spin" />
                            </span>
                            
                            <span>
                                Uploading...
                            </span>
                        </div>
                    </x-slot>
                </x-input>

                <x-input class="col-span-2" type="file" label="Requirement 3" wire:model="requirement_3" shadowless>
                    <x-slot name='corner' wire:target='requirement_3' wire:loading>
                        <div class="flex items-center justify-center gap-2">
                            <span>
                                <x-icon name="arrow-path" class="w-5 h-5 animate-spin" />
                            </span>
                            
                            <span>
                                Uploading...
                            </span>
                        </div>
                    </x-slot>
                </x-input>
            </div>
            
            <x-slot name="footer" class="flex justify-end gap-x-4">
                <x-button flat label="Cancel" x-on:click="close" />
                <x-button wire:loading.attr="disabled" wire:click="store" spinner="store" label="Submit" />
            </x-slot>
        </div>
    @elseif($registrationStatus == App\Enums\Status::ForReview)
        <div class="w-full flex flex-col items-center justify-center">
            <h1 class="text-2xl font-semibold text-center">Your registration has been submitted and current under validation.</h1>

            <img class="w-[400px] h-[400px]" src="{{ asset('assets/svg/for-review.svg') }}" alt="For Review and Validation"/>
        </div>
    @elseif($registrationStatus == App\Enums\Status::ForReSubmission)
        <div class="w-full flex flex-col items-center justify-center">
            <x-alert title="Your registration has been declined and need for resubmission." info>
                <span class="font-medium">Admin's Remarks:</span> {{ $savedRequirements->remarks }}
            </x-alert>

            <div class="pt-5 w-full space-y-3">

                @if($savedRequirements->requirement_1->status == App\Enums\Status::Declined)
                    <x-input type="file" label="Requirement 1" wire:model="requirement_1" shadowless>
                        <x-slot name='corner' wire:target='requirement_1' wire:loading>
                            <div class="flex items-center justify-center gap-2">
                                <span>
                                    <x-icon name="arrow-path" class="w-5 h-5 animate-spin" />
                                </span>
                                
                                <span>
                                    Uploading...
                                </span>
                            </div>
                        </x-slot>
                    </x-input>
                @endif

                @if($savedRequirements->requirement_2->status == App\Enums\Status::Declined)
                    <x-input type="file" label="Requirement 2" wire:model="requirement_2" shadowless>
                        <x-slot name='corner' wire:target='requirement_2' wire:loading>
                            <div class="flex items-center justify-center gap-2">
                                <span>
                                    <x-icon name="arrow-path" class="w-5 h-5 animate-spin" />
                                </span>
                                
                                <span>
                                    Uploading...
                                </span>
                            </div>
                        </x-slot>
                    </x-input>
                @endif

                @if($savedRequirements->requirement_3->status == App\Enums\Status::Declined)
                    <x-input type="file" label="Requirement 3" wire:model="requirement_3" shadowless>
                        <x-slot name='corner' wire:target='requirement_3' wire:loading>
                            <div class="flex items-center justify-center gap-2">
                                <span>
                                    <x-icon name="arrow-path" class="w-5 h-5 animate-spin" />
                                </span>
                                
                                <span>
                                    Uploading...
                                </span>
                            </div>
                        </x-slot>
                    </x-input>
                @endif
            </div>

            <x-slot name="footer" class="flex justify-end gap-x-4">
                <x-button flat label="Cancel" x-on:click="close" />
                <x-button wire:loading.attr="disabled" wire:click="store" spinner="store" label="Submit" />
            </x-slot>
        </div>
    @endif
</x-modal-card>