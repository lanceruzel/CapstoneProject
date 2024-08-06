<x-layouts.store-layout wire:ignore.self>
    <div class="flex w-full items-center justify-between">
        <h1 class="text-3xl font-semibold">Return Product</h1>
    </div>

    <div class="mt-5">
        <livewire:Return.return-products-table />
    </div>

    <livewire:Return.return-request-view-modal />
    <livewire:Return.return-create-order-modal />
</x-layouts.store-layout>