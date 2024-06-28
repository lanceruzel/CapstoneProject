<x-layouts.main-layout>
    <div class="w-full max-w-[1000px] mx-auto px-3 h-full">
        <div class="pb-2.5">
            <div class="flex max-sm:items-start items-center max-sm:flex-col justify-between gap-4">
                <p class="max-md:text-2xl text-4xl font-semibold">My Cart</p>
    
                <x-button label="Orders" icon='truck' href="{{ route('orders') }}" />
            </div>

            <!-- content -->
        </div>
    </div>

    {{-- <livewire:Ordering.cart-total-checkout /> --}}
</x-layouts.main-layout>