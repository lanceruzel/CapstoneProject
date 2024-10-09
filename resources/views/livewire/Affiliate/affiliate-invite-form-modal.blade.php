<x-modal-card name="affiliateInviteFormModal" width='md' title="Invite Affiliate" align='center' x-cloak x-on:close="$dispatch('clearAffiliateInviteFormModalData')" blurless wire:ignore.self>  
    <div class="flex flex-col gap-2 items-start text-gray-600 overflow-auto">
        <x-input label="Promoter Email" wire:model="email" shadowless />

        <div class="grid grid-cols-2 gap-3">
            <x-input suffix="%" label="Commission Per Order" wire:model="commissionRate" shadowless />
            <x-input suffix="%" label="Discount" wire:model="discount" shadowless />
        </div>

        <x-input label="Affiliate Code" description="(Note: Min of 10 and max of 15 alphanumeric characters in all capital letters (A-Z, 0-9) e.g. FITGEAR123)" wire:model="affiliateCode" shadowless />
    
        <x-slot name="footer" class="flex justify-end gap-x-4">
            <x-button flat wire:loading.attr="disabled" label="Close" x-on:click="close" />
            <x-button wire:loading.attr="disabled" wire:click="sendAffiliateInvitation" spinner="sendAffiliateInvitation" label="Send Invitation" />
        </x-slot>
    </div>
 </x-modal-card>