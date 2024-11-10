<x-layouts.store-layout wire:ignore.self>
    <div class="flex w-full items-center justify-between">
        <h1 class="text-3xl font-semibold">Request Return List</h1>
    </div>

    <div class="mt-5">
        <livewire:Report.return-products-table />
    </div>

    <livewire:Report.view-return-request-modal />
    <livewire:Report.return-create-order-modal />
    <livewire:Report.return-request-decline-modal />
</x-layouts.store-layout>