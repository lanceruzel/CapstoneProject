<x-layouts.store-layout wire:ignore.self>
    <div class="flex w-full items-center justify-between">
        <h1 class="text-3xl font-semibold">Order Management</h1>

        <x-button icon="plus" primary label="Add Product" onclick="alert('dsad')"/>
    </div>

    <div class="mt-5">
        <livewire:Order.orders-table />
    </div>

    <livewire:Order.order-information-modal />
</x-layouts.store-layout>