<x-layouts.admin-layout wire:ignore.self>
    <div class="flex w-full items-center justify-between">
        <h1 class="text-3xl font-semibold">User Information Management</h1>
    </div>

    <div class="mt-5">
        <livewire:UserManagement.user-table />
    </div>

   <livewire:UserManagement.user-details-modal />
   <livewire:UserManagement.user-view-products />
   <livewire:UserManagement.user-view-orders />
   <livewire:UserManagement.view-order-details />
   <livewire:UserManagement.view-affiliates />
   <livewire:UserManagement.view-payout-history />
   <livewire:Product.product-view-modal />
</x-layouts.admin-layout>