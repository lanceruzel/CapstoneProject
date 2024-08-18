<x-layouts.store-layout wire:ignore.self>
    <div class="flex w-full items-center justify-between">
        <h1 class="text-3xl font-semibold">Ordered Products</h1>
    </div>

    <div class="mt-5">
        <livewire:Order.ordered-products-table />
    </div>

    <livewire:Order.order-information-modal />
</x-layouts.store-layout>