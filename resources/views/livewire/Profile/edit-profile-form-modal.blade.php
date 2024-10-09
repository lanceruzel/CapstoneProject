<x-modal-card name="editProfileFormModal" title="Update Profile" width="xl" align='center' x-cloak x-on:close="$dispatch('clearEditProfileFormModalData')" blurless wire:ignore.self>  
    <div class="flex flex-col gap-2 items-start text-gray-600 overflow-auto">
        <div class="space-y-2 w-full">

            <div class="flex items-start justify-center gap-8 w-full">
                <!-- Profile -->
                <div>
                    <div class="relative h-28 w-28 md:h-40 md:w-40 rounded-full overflow-hidden border-[6px] bg-slate-400 border-gray-100 shrink-0"> 
                        @if($profileDP == null)
                            <div class="w-full h-full object-cover absolute bottom-10 right-1">
                                <i class="ri-user-3-fill ri-10x"></i>
                            </div>
                        @else
                            <a href="{{ is_object($profileDP) && method_exists($profileDP, 'temporaryUrl') ? $profileDP->temporaryUrl() : asset('uploads') . '/' . $profileDP }}">
                                <img src="{{ is_object($profileDP) && method_exists($profileDP, 'temporaryUrl') ? $profileDP->temporaryUrl() : asset('uploads') . '/' . $profileDP }}" accept="image/png, image/jpeg" class="w-full h-full absolute object-cover">
                            </a>
                        @endif
                    </div>
    
                    <label wire:target='profileDP' wire:loading.attr='disabled' class="flex flex-col justify-center items-center cursor-pointer w-full text-gray-700">
                        <input class="hidden" type="file" accept="image/png, image/jpg, image/jpeg" wire:model="profileDP">
    
                        <div wire:target='profileDP' wire:loading.remove class="mt-2">Upload Picture</div>
    
                        <div wire:target='profileDP' wire:loading class="mt-2 flex items-center justify-center gap-3">
                            <span>Loading...</span>
                        </div>
                    </label>
                </div>

                <div class="w-full space-y-3">
                    <x-input label="Username" wire:model="username" shadowless /> 

                    @if($account->role == App\Enums\UserType::Store)
                        <x-input disabled label="Store Name" wire:model="name" shadowless />  
                    @else
                        <x-input label="First Name" wire:model="firstName" shadowless />  
                        <x-input label="Last Name" wire:model="lastName" shadowless />  
                    @endif
                </div> 
            </div>

            <x-input label="Email" wire:model="email" shadowless />
            <x-textarea label="Bio" wire:model="profileBio" shadowless />
        </div>
        
        <x-slot name="footer" class="flex justify-end gap-x-4">
            <x-button wire:loading.attr="disabled" flat label="Cancel" x-on:click="close" />
            <x-button wire:loading.attr="disabled" wire:click="update" spinner="update" label="Update" />
        </x-slot>
    </div>
</x-modal-card>