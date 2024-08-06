<div class="flex flex-col gap-5 mt-3">
    @if(count($orders) > 0)
        @foreach ($orders as $order)
            <livewire:Order.Return.return-order-container :order="$order" />
        @endforeach
    @else
        <div class="flex items-center justify-center w-full h-full">
            <img src="{{ asset('assets/svg/no-data-1.svg') }}" class="max-w-[400px] max-h-[400px]" alt="No data found"/>
        </div>
    @endif
</div>
