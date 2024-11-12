<x-modal-card name="storeRegistrationFormModal" title="Store Registration" align='center' x-cloak x-on:close="$dispatch('clearstoreRegistrationData')" blurless wire:ignore.self>
    @if($registrationStatus == App\Enums\Status::ForSubmission)
        <div class="flex flex-col items-start justify-center gap-5 text-gray-600">
            
            <div class="flex flex-col gap-1 w-full">
                <div>
                    <h1 class="col-span-2 text-xl font-semibold">Store Information</h1>
                    <small class="text-gray-500">(Note: The following information will be used to contact your store)</small>
                </div>
                
                <div class="grid grid-cols-2 gap-3 w-full">
                    <x-phone
                        class="max-lg:col-span-2"
                        shadowless
                        wire:model="contact"
                        label="Store Number"
                        :mask="[
                            '(###) ###-####',               // US format
                            '+# ### ###-####',              // Generic international format
                            '+## ## ####-####',             // Europe format
                            '+### ## ### ####',             // UK and other similar regions
                            '+## (##) ####-####',           // Canada format
                            '+## (#) ####-####'             // General international format
                        ]"
                    />
                    <x-input class="max-lg:col-span-2" label="Store Email" wire:model="email" shadowless />

                    <x-select
                        class="max-lg:col-span-2"
                        label="Country"
                        wire:model="country"
                        placeholder="Select Country"
                        :options="$countryOptions"
                        option-label="name"
                        option-value="value"
                        searchable
                        shadowless
                        x-on:selected="Livewire.dispatch('updatedCountry')"
                    />
        
                    <x-select
                        class="max-lg:col-span-2"
                        label="State"
                        wire:model="state"
                        placeholder="Select State"
                        :options="$stateOptions"
                        option-label="name"
                        option-value="value"
                        searchable
                        shadowless
                    />
                </div>
            </div>

            <div class="flex flex-col gap-1 w-full">
                <div>
                    <h1 class="col-span-2 text-xl font-semibold">Paypal Account Information</h1>
                    <small class="text-gray-500">(Note: Make sure you enter correct details)</small>
                </div>
            
                <div class="grid grid-cols-2 gap-3 w-full">
                    <x-input class="max-lg:col-span-2" label="Account Name" wire:model="paypalAccountName" shadowless />
                    <x-input class="max-lg:col-span-2" label="Email" wire:model="paypalEmail" shadowless />
                </div>
            </div>

            <div class="flex flex-col gap-1">
                <div>
                    <h1 class="col-span-2 text-xl font-semibold">Store Requirements</h1>
                    {{-- <small class="text-gray-500">(Note: The following information will be used to contact your store)</small> --}}
                </div>
                
                <div class="gap-3 w-full">
                    @foreach(array_slice((array) $savedRequirements, 0, -2) as $key => $requirement) 
                        @if($key == 'valid_id')
                            <div class="grid grid-cols-2 mt-3 gap-3" x-data="{ 
                                    uploading: false, 
                                    progress: 0,
                                }"
                                x-on:livewire-upload-start="uploading = true"
                                x-on:livewire-upload-finish="uploading = false; progress = 0"
                                x-on:livewire-upload-error="uploading = false"
                                x-on:livewire-upload-progress="progress = $event.detail.progress"
                            >
                                <x-select
                                    class="max-lg:col-span-2"
                                    label="Select ID Type"
                                    placeholder="Select one"
                                    :options="['Driver\'s License', 'National Identity Card', 'Passport', 'Voter ID Card', 'Social Security Card', 'Tax Identification Number (TIN)', 'Health Insurance Card', 'State or Provincial ID Cards', 'Work Permit or Work ID Card']"
                                    shadowless
                                    wire:model="validIdType"
                                />

                                <x-input type="file" label="Upload ID Picture" class="max-lg:col-span-2" wire:model='valid_id' shadowless>
                                    <x-slot name='corner' wire:target='valid_id' wire:loading>
                                        <div class="flex flex-col gap-3 items-center justify-center w-[150px]">
                                            <div x-show="uploading" class="w-full max-w-xs">
                                                <div class="bg-gray-400 rounded-full h-4 dark:bg-gray-700 w-full relative">
                                                    <div class="bg-teal-600 h-4 rounded-full" x-bind:style="{ width: `${progress}%` }"></div>
                                                    
                                                    <div class="absolute inset-0 flex justify-center items-center">
                                                        <span class="text-xs text-white font-semibold" x-text="`${progress}%`"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </x-slot>
                                </x-input>
                            </div>
                        @else
                            <div x-data="{ 
                                    uploading: false, 
                                    progress: 0,
                                }"
                                x-on:livewire-upload-start="uploading = true"
                                x-on:livewire-upload-finish="uploading = false; progress = 0"
                                x-on:livewire-upload-error="uploading = false"
                                x-on:livewire-upload-progress="progress = $event.detail.progress"
                            >
                                <x-input type="file" class="mt-3" wire:model='{{ $key }}' shadowless>
                                    <x-slot name='label'>
                                        @switch($key)
                                            @case('dti_permit')
                                                DTI Permit
                                                @break
    
                                            @case('bir_registration')
                                                BIR Registration
                                                @break
    
                                            @case('tax_compliance')
                                                Tax Compliance
                                                @break
    
                                            @case('business_license')
                                                Business License
                                                @break
    
                                            @case('mayors_permit')
                                                Mayor's Permit
                                                @break
    
                                            @case('business_permit')
                                                Business Permit
                                                @break
    
                                            @case('health_and_safety_permit')
                                                Health and safety permit
                                                @break
    
                                            @default
                                        @endswitch
                                    </x-slot>
    
                                    <x-slot name='corner' wire:target='{{ $key }}' wire:loading>
                                        <div class="flex flex-col gap-3 items-center justify-center w-[150px]">
                                            <div x-show="uploading" class="w-full max-w-xs">
                                                <div class="bg-gray-400 rounded-full h-4 dark:bg-gray-700 w-full relative">
                                                    <div class="bg-teal-600 h-4 rounded-full" x-bind:style="{ width: `${progress}%` }"></div>
                                                    
                                                    <div class="absolute inset-0 flex justify-center items-center">
                                                        <span class="text-xs text-white font-semibold" x-text="`${progress}%`"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </x-slot>
                                </x-input>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
            <x-slot name="footer" class="flex justify-end gap-x-4">
                <x-button wire:loading.attr="disabled" flat label="Cancel" x-on:click="close" />
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
                @foreach (array_slice((array) $savedRequirements, 0, -2) as $key => $requirement)
                    @if($requirement->status == App\Enums\Status::Declined)
                        @if($key == 'valid_id')
                            <div class="grid grid-cols-2 mt-3 gap-3" x-data="{ 
                                uploading: false, 
                                progress: 0,
                                }"
                                x-on:livewire-upload-start="uploading = true"
                                x-on:livewire-upload-finish="uploading = false; progress = 0"
                                x-on:livewire-upload-error="uploading = false"
                                x-on:livewire-upload-progress="progress = $event.detail.progress"
                            >
                                <x-select
                                    class="max-lg:col-span-2"
                                    label="Select ID Type"
                                    placeholder="Select one"
                                    :options="['Driver\'s License', 'National Identity Card', 'Passport', 'Voter ID Card', 'Social Security Card', 'Tax Identification Number (TIN)', 'Health Insurance Card', 'State or Provincial ID Cards', 'Work Permit or Work ID Card']"
                                    shadowless
                                    wire:model="validIdType"
                                />

                                <x-input type="file" label="Upload ID Picture" class="max-lg:col-span-2" wire:model='valid_id' shadowless>
                                    <x-slot name='corner' wire:target='valid_id' wire:loading>
                                        <div class="flex flex-col gap-3 items-center justify-center w-[150px]">
                                            <div x-show="uploading" class="w-full max-w-xs">
                                                <div class="bg-gray-400 rounded-full h-4 dark:bg-gray-700 w-full relative">
                                                    <div class="bg-teal-600 h-4 rounded-full" x-bind:style="{ width: `${progress}%` }"></div>
                                                    
                                                    <div class="absolute inset-0 flex justify-center items-center">
                                                        <span class="text-xs text-white font-semibold" x-text="`${progress}%`"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </x-slot>
                                </x-input>
                            </div>
                        @else
                            <div x-data="{ 
                                uploading: false, 
                                progress: 0,
                                }"
                                x-on:livewire-upload-start="uploading = true"
                                x-on:livewire-upload-finish="uploading = false; progress = 0"
                                x-on:livewire-upload-error="uploading = false"
                                x-on:livewire-upload-progress="progress = $event.detail.progress"
                            >
                                <x-input type="file" class="mt-3" wire:model='{{ $key }}' shadowless>
                                    <x-slot name='label'>
                                        @switch($key)
                                            @case('dti_permit')
                                                DTI Permit
                                                @break

                                            @case('bir_registration')
                                                BIR Registration
                                                @break

                                            @case('tax_compliance')
                                                Tax Compliance
                                                @break

                                            @case('business_license')
                                                Business License
                                                @break

                                            @case('mayors_permit')
                                                Mayor's Permit
                                                @break

                                            @case('business_permit')
                                                Business Permit
                                                @break

                                            @case('health_and_safety_permit')
                                                Health and safety permit
                                                @break

                                            @default
                                        @endswitch
                                    </x-slot>

                                    <x-slot name='corner' wire:target='{{ $key }}' wire:loading>
                                        <div class="flex flex-col gap-3 items-center justify-center w-[150px]">
                                            <div x-show="uploading" class="w-full max-w-xs">
                                                <div class="bg-gray-400 rounded-full h-4 dark:bg-gray-700 w-full relative">
                                                    <div class="bg-teal-600 h-4 rounded-full" x-bind:style="{ width: `${progress}%` }"></div>
                                                    
                                                    <div class="absolute inset-0 flex justify-center items-center">
                                                        <span class="text-xs text-white font-semibold" x-text="`${progress}%`"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </x-slot>
                                </x-input>
                            </div>
                        @endif
                    @endif
                @endforeach
            </div>

            <x-slot name="footer" class="flex justify-end gap-x-4">
                <x-button wire:loading.attr="disabled" flat label="Cancel" x-on:click="close" />
                <x-button wire:loading.attr="disabled" wire:click="store" spinner="store" label="Submit" />
            </x-slot>
        </div>
    @endif
</x-modal-card>