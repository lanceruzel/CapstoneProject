<x-modal-card name="affiliatePayoutRequestForm" width='md' title="Payout Request" align='center' x-cloak x-on:close="$dispatch('clearaffiliatePayoutRequestFormModalData')" blurless wire:ignore.self>  
    <div class="flex flex-col gap-2 items-start text-gray-600 overflow-auto">

        <x-input label="Account Name" wire:model="accountName" shadowless />
        <x-input label="Paypal Email" wire:model="paypalEmail" shadowless />
        <x-input prefix="$" label="Enter Amount" wire:model="amount" corner="Min: $20" shadowless />

        <x-slot name="footer" class="flex justify-end gap-x-4">
            <x-button flat wire:loading.attr="disabled" label="Close" x-on:click="close" />
            <x-button wire:loading.attr="disabled" wire:click="sendPayout" spinner="sendPayout" label="Send Request" />
        </x-slot>
    </div>
 </x-modal-card>