<x-layouts.main-layout>
    <div class="w-full max-w-[1000px] mx-auto px-3 h-full">
        <div class="pb-2.5">
            <div class="flex items-center justify-start gap-3">
                <x-mini-button href="{{ route('cart') }}" rounded icon="chevron-left" flat gray />
                <p class="max-md:text-2xl text-4xl font-semibold">Orders</p>
            </div>
            
            <div class="uk-margin-medium-top !mt-3">
                <ul class="uk-flex-start" uk-tab uk-switcher="connect: #orders-switcher; animation: uk-animation-slide-right-medium, uk-animation-slide-left-medium">
                    <li class="uk-active">
                        <a href="#" class="!text-sm font-medium">All</a>
                    </li>
    
                    <li>
                        <a href="#" class="!text-sm font-medium">To Receive</a>
                    </li>

                    <li>
                        <a href="#" class="!text-sm font-medium">Completed</a>
                    </li>

                    <li>
                        <a href="#" class="!text-sm font-medium">Cancelled</a>
                    </li>

                    <li>
                        <a href="#" class="!text-sm font-medium">Return</a>
                    </li>
                </ul>
            </div>

            <ul class="uk-switcher uk-margin w-full flex flex-col justify-center items-center" id="orders-switcher">
                <!-- All -->
                <li class="w-full md:px-5">
                    <livewire:Order.orders-container status="all" />
                </li>
            
                <!-- To Receive -->
                <li class="w-full md:px-5">
                    <livewire:Order.orders-container status="to_receieved" />
                </li>
            
                <!-- Completed -->
                <li class="w-full md:px-5">
                    <livewire:Order.orders-container status="received" />
                </li>
            
                <!-- Cancelled -->
                <li class="w-full md:px-5">
                    <livewire:Order.orders-container status="cancelled" />
                </li>

                <!-- Return -->
                <li class="w-full md:px-5">
                    <livewire:Order.Return.return-orders-container />
                </li>
            </ul>

            <!-- content -->
        </div>
    </div>

    <livewire:Order.product-feedback-form-modal />
    <livewire:Report.product-report-form-modal />
    <livewire:Return.return-product-form-modal />
    <livewire:Order.Return.update-return-tracking-number-modal />
</x-layouts.main-layout>