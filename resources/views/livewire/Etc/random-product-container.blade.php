<div class="bg-white rounded-xl shadow-sm p-5 px-6 border1 dark:bg-dark2">
                    
    <div class="flex justify-between text-black dark:text-white">
        <h3 class="font-bold text-base">Products you might want</h3>

        <x-mini-button rounded icon="arrow-path" wire:click='$refresh' spinner outline flat primary interaction:solid />
    </div>

    <div class="relative capitalize font-medium text-sm text-center mt-4 mb-2 uk-slider" tabindex="-1" uk-slider="autoplay: true;finite: true">

        <div class="overflow-hidden uk-slider-container">
           
            <ul class="-ml-2 uk-slider-items w-[calc(100%+0.5rem)]" style="transform: translate3d(-164.2px, 0px, 0px);">
                
                @if(count($products) > 0)
                    @foreach ($products as $product)
                        <li class="w-1/2 pr-2" tabindex="-1">
                            <div uk-toggle="target: #view-product" wire:click="$dispatch('view-product-info', { id: {{ $product->id }}, feedback: true })">
                                <div class="relative overflow-hidden rounded-lg">
                                    <div class="relative w-full md:h-40 h-full">
                                        <img src="{{ asset('uploads/products') . '/' . json_decode($product->images)[0] }}" alt="" class="object-cover w-full h-full inset-0">
                                    </div> 
                                    <div class="absolute right-0 top-0 m-2 bg-white/60 rounded-full py-0.5 px-2 text-sm font-semibold dark:bg-slate-800/60">{{ $product->priceRange() }}</div>
                                </div>
                                <div class="mt-3 w-full text-ellipsis line-clamp-2">{{ $product->name }}</div>
                            </div>
                        </li>
                    @endforeach
                @else
                    <div class="flex items-center justify-center">
                        There are currently no products available at the moment
                    </div>
                @endif

            </ul>

            <button type="button" class="absolute bg-white rounded-full top-16 -left-4 grid w-9 h-9 place-items-center shadow dark:bg-dark3" uk-slider-item="previous"> 
                <i class="ri-arrow-left-s-line ri-lg"></i>
            </button>
            
            <button type="button" class="absolute -right-4 bg-white rounded-full top-16 grid w-9 h-9 place-items-center shadow dark:bg-dark3 uk-invisible" uk-slider-item="next"> 
                <i class="ri-arrow-right-s-line ri-lg"></i>
            </button>
        </div>
    </div>
</div>