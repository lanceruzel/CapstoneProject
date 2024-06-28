<div>
    <div class="relative uk-slider py-2.5" wire:ignore tabindex="-1" uk-slider="finite: true">
        <div class="overflow-hidden uk-slider-container">
            <ul class="uk-slider-items w-[calc(100%+0.10px)] capitalize text-sm font-semibold" style="transform: translate3d(0px, 0px, 0px);">
                    <li class="w-auto p-2.5 uk-active py-2" tabindex="-1"> 
                        <div class="cursor-pointer hover:no-underline hover:text-gray-700 px-4 py-2 rounded-lg bg-slate-200 inline-block hover:shadow-md hover:scale-105 transition-all" wire:click="$dispatch('change-selected-product-category', { category: 'All' })">All</div> 
                    </li>
                    
                @foreach ($categories as $category)
                    <li class="w-auto p-2.5 uk-active py-2" tabindex="-1"> 
                        <div class="cursor-pointer hover:no-underline hover:text-gray-700 px-4 py-2 rounded-lg bg-slate-200 inline-block hover:shadow-md hover:scale-105 transition-all" wire:click="$dispatch('change-selected-product-category', { category: '{{ $category['name'] }}' })">{{ $category['name'] }}</div> 
                    </li>
                @endforeach
            </ul>
        </div>
                
        <a class="hover:no-underline hover:text-gray-700 active:scale-95 transition-all absolute left-0 -translate-y-1/2 top-1/2 flex items-center w-16 h-12 p-2.5 justify-start bg-gradient-to-r from-gray-100" href="#" uk-slider-item="previous"> <i class="ri-arrow-left-s-line ri-2x"></i> </a>
        <a class="hover:no-underline hover:text-gray-700 active:scale-95 transition-all absolute right-0 -translate-y-1/2 top-1/2 flex items-center w-16 h-12 p-2.5 justify-end bg-gradient-to-l from-gray-100 uk-invisible" href="#" uk-slider-item="next"> <i class="ri-arrow-right-s-line ri-2x"></i> </a>
    </div>

    <div>
        Showing result: <span class="font-semibold">{{ $selectedCategory }}</span>
    </div>
</div>