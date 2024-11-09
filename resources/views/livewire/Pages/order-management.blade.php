<x-layouts.store-layout wire:ignore.self>
    <div class="flex w-full items-center justify-between">
        <h1 class="text-3xl font-semibold">Order Management</h1>
    </div>

    <div class="mt-5">
        <livewire:Order.orders-table />
    </div>

    <livewire:Order.order-information-modal />
    <livewire:Order.order-cancellation-modal />
</x-layouts.store-layout>