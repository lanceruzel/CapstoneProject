<x-layouts.main-layout>
    <div class="w-full max-w-[1000px] mx-auto px-3 h-full">
        <div class="pb-2.5">
            <div class="flex max-sm:items-start items-center max-sm:flex-col justify-between gap-4">
                <p class="max-md:text-2xl text-4xl font-semibold">Market</p>
    
                <div class="flex items-center justify-end max-md:w-full sm:w-7/12 md:w-5/12">
                    <x-input icon="magnifying-glass" wire:model.live.debounce.200ms="search" placeholder="Search" shadowless />
                </div>
            </div>

            <livewire:Product.product-categories-container />

            <livewire:Product.products-container />
        </div>
    </div>

    <livewire:Product.product-view-modal />
</x-layouts.main-layout>