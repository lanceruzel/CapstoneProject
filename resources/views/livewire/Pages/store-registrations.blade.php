<x-layouts.admin-layout wire:ignore.self>
    <div class="flex w-full items-center justify-between">
        <h1 class="text-3xl font-semibold">Store Registrations</h1>
    </div>

    <div class="mt-5">
        <livewire:StoreRegistration.store-registrations-table />
    </div>

    <livewire:StoreRegistration.view-store-registration-modal />
    <livewire:StoreRegistration.p-d-f-view-modal />
</x-layouts.admin-layout>