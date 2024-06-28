<div class="w-full">
    @if(count($products) > 0)
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 py-5">
            @foreach ($products as $key => $product)
                <livewire:Product.product-container :product="$product" :id="$product->id" wire:key="{{ $key }}-product-{{ $product->id }}" />
            @endforeach
        </div>
    @else
        <div class="flex items-center justify-center w-full h-full">
            <img src="{{ asset('assets/svg/no-data-1.svg') }}" alt="No data found" class="max-w-[500px] max-h-[500px]" />
        </div>
    @endif
</div>