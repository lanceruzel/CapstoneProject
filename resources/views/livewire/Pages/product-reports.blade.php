<x-layouts.admin-layout wire:ignore.self>
    <div class="flex w-full items-center justify-between">
        <h1 class="text-3xl font-semibold">Product Reports</h1>
    </div>

    <div class="mt-5">
        <livewire:Report.reports-table />
    </div>

    <livewire:Report.view-report-modal />
</x-layouts.admin-layout>