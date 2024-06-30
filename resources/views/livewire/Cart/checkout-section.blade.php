<div class="max-md:h-20 md:h-28 w-full border-t-2 bg-white fixed bottom-0 left-0 pe-0 md:pe-5 lg:pe-10 z-[1]">
    <div class="flex max-md:flex-row md:flex-col max-md:py-0 md:py-5 max-md:items-center md:items-end justify-between max-md:h-20 md:h-24 w-full px-5">
        <div class="pb-3">
            Total: <span class="font-semibold">${{ number_format($total, 2) }}</span>
        </div>

        <x-button label="Checkout" />
    </div>
</div>
