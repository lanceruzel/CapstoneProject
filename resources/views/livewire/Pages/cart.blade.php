<x-layouts.main-layout>
    <div class="w-full max-w-[1000px] mx-auto h-full max-md:mb-20 md:mb-28">
        <div class="pb-2.5">
            <div class="flex max-sm:items-start items-center max-sm:flex-col justify-between gap-4">
                <p class="max-md:text-2xl text-4xl font-semibold">My Cart</p>
    
                <x-button label="Orders" icon='truck' href="{{ route('orders') }}" />
            </div>

            <!-- content -->
            <livewire:Cart.cart-items-container />
        </div>
    </div>

    <livewire:Cart.checkout-section />
</x-layouts.main-layout>