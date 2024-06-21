<x-layouts.admin-layout wire:ignore.self>
    <div class="flex w-full items-center justify-between">
        <h1 class="text-3xl font-semibold">Store Registrations</h1>
    </div>

    <div class="!mt-10 md:p-10">
        <livewire:StoreRegistration.store-registrations-table />
    </div>
</x-layouts.admin-layout>