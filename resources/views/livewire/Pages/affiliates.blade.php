<x-layouts.store-layout wire:ignore.self>
    <div class="flex w-full items-center justify-between">
        <h1 class="text-3xl font-semibold">Affiliate Management</h1>

        <x-button icon="plus" primary label="Invite Affiliate" onclick="$openModal('affiliateInviteFormModal')"/>
    </div>

    <div class="mt-5">
        <livewire:Affiliate.store-affiliates-table />
    </div>

    <livewire:Affiliate.affiliate-invite-form-modal />
</x-layouts.store-layout>