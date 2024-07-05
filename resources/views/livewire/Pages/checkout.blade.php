<x-layouts.main-layout>
    <div class="w-full max-w-[1000px] mx-auto h-full max-md:mb-20 md:mb-28">
        <div class="pb-2.5">
            <div class="flex items-center gap-3">
                <x-mini-button rounded icon="arrow-left" flat gray href="{{ route('cart') }}" />
                <p class="max-md:text-2xl text-4xl font-semibold">Checkout</p>
            </div>
            
            <!-- content -->

            <div class="w-full">
                <livewire:Checkout.ShippingInformation.shipping-information-container />

                <livewire:Checkout.Orders.orders-checkout-container />

                <livewire:Checkout.Payment.payment-method-container />

                <livewire:Checkout.TotalSummary.order-total-summary />
            </div>
        </div>
    </div>

    <livewire:Checkout.ShippingInformation.user-saved-shipping-addresses-modal />
    <livewire:Checkout.ShippingInformation.shipping-address-form-modal />
</x-layouts.main-layout>