<x-modal-card name="affiliateUpdateFormModal" width='md' title="Invite Affiliate" align='center' x-cloak x-on:close="$dispatch('clearAffiliateUpdateFormModalData')" blurless wire:ignore.self>  
    @if($affiliate)
        <div class="flex flex-col gap-2 items-start text-gray-600 overflow-auto">
            <x-input label="Promoter Email" disabled wire:model="email" shadowless />

            <div class="grid grid-cols-2 gap-3">
                <x-input suffix="%" label="Commission Per Order" wire:model="commissionRate" shadowless />
                <x-input suffix="%" label="Discount" wire:model="discount" shadowless />
            </div>

            <x-input label="Affiliate Code" description="(Note: Min of 10 and max of 15 alphanumeric characters in all capital letters (A-Z, 0-9) e.g. FITGEAR123)" wire:model="affiliateCode" shadowless />
        
            <x-slot name="footer" class="flex justify-end gap-x-4">
                <x-button flat wire:loading.attr="disabled" label="Close" x-on:click="close" />
                <x-button wire:loading.attr="disabled" wire:click="updateConfirmation" spinner="update" label="Activate Affiliate" />
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