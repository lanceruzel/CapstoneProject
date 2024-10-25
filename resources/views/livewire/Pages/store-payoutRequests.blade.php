<x-layouts.store-layout wire:ignore.self>
    <div class="flex w-full items-center justify-between">
        <h1 class="text-3xl font-semibold">Payout Requests</h1>
    </div>

    <div class="mt-5">
        <livewire:Affiliate.payout-table />
    </div>

    <livewire:Affiliate.payout-modal />
</x-layouts.store-layout>