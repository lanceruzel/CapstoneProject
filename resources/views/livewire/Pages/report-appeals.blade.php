<x-layouts.admin-layout wire:ignore.self>
    <div class="flex w-full items-center justify-between">
        <h1 class="text-3xl font-semibold">Report Appeals</h1>
    </div>

    <div class="mt-5">
        <livewire:Appeal.report-appeals-table />
    </div>

    <livewire:Appeal.admin-view-appeal-conversation-modal />
</x-layouts.admin-layout>