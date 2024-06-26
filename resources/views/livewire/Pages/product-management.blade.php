<x-layouts.store-layout wire:ignore.self>
    <div class="flex w-full items-center justify-between">
        <h1 class="text-3xl font-semibold">Product Management</h1>

        <x-button icon="plus" primary label="Add Product" onclick="$openModal('productFormModal')"/>
    </div>

    <div class="mt-5">
        <livewire:Product.products-table />
    </div>

    <livewire:Product.product-form-modal />
</x-layouts.store-layout>