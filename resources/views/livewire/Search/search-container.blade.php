<div class="pt-3">
    <x-input icon="magnifying-glass" wire:model.live.debounce.200ms="search" placeholder="Search" shadowless />

    <ul uk-accordion="multiple: true">
        <li class="uk-open">
            <button class="uk-accordion-title !text-lg !text-gray-700 mt-3 group">
                People
                <i class="ri-arrow-right-s-line justify-self-end align-middle transition-all max-xl:hidden group-aria-expanded:rotate-90"></i>
            </button>

            <!-- User -->
            <div class="uk-accordion-content !mt-0">
                <div class="divide-y">
                    @if($users)
                        @foreach ($users as $user)
                            <!-- user -->
                            <div class="w-full flex items-center gap-3 hover:no-underline py-2">
                                <div>
                                    <img src="https://static.everypixel.com/ep-pixabay/0329/8099/0858/84037/3298099085884037069-head.png" alt="" class="bg-gray-200 rounded-full w-10 h-10">
                                </div>
            
                                <div class="flex-1">
                                    <a href="{{ route('profile', $user->username) }}" class="leading-snug hover:no-underline">
                                        <p class="font-semibold text-md text-gray-700">{{ $user->first_name . ' ' . $user->last_name }}</p>
                                        <small class="text-gray-700">
                                            <span class="font-semibold me-1">12.3k</span>followers
                                        </small>
                                    </a>
                                </div>
                                
                                <x-button white class="!text-slate-500" label="Message" href="{{ route('message', $user->user->username) }}" />
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </li>

        <li class="uk-open">
            <button class="uk-accordion-title !text-lg !text-gray-700 mt-3 group">
                Store
                <i class="ri-arrow-right-s-line justify-self-end align-middle transition-all max-xl:hidden group-aria-expanded:rotate-90"></i>
            </button>

            <!-- User -->
            <div class="uk-accordion-content !mt-0">
                <div class="divide-y">
                    @if($stores)
                        @foreach($stores as $store)
                            <!-- user -->
                            <div class="w-full flex items-center gap-3 hover:no-underline py-2">
                                    <div>
                                        <img src="https://static.everypixel.com/ep-pixabay/0329/8099/0858/84037/3298099085884037069-head.png" alt="" class="bg-gray-200 rounded-full w-10 h-10">
                                    </div>
                
                                    <div class="flex-1">
                                        <a href="{{ route('profile', $store->username) }}" class="leading-snug hover:no-underline">
                                            <p class="font-semibold text-md text-gray-700">{{ $store->name }}</p>

                                            <small class="text-gray-700">
                                                <span class="font-semibold me-1">{{ count($store->user->products) }}</span>products
                                            </small>
                                        </a>
                                    </div>
                
                                    <x-button white class="!text-slate-500" label="Message" href="{{ route('message', $store->user->username) }}" />
                                </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </li>

        <li class="uk-open">
            <button class="uk-accordion-title !text-lg !text-gray-700 mt-3 group">
                Products
                <i class="ri-arrow-right-s-line justify-self-end align-middle transition-all max-xl:hidden group-aria-expanded:rotate-90"></i>
            </button>

            <!-- Product -->
            <div class="uk-accordion-content !mt-0">
                <div class="space-y-3 divide-y">
                    @if($products)
                        @foreach($products as $product)
                            <!-- product -->
                            <div class="w-full flex items-start rounded-md p-3 gap-3 shadow">
            
                                <!-- product img -->
                                <div class="flex items-center">
                                    <div class="h-28 w-28 !max-h-28 !max-w-28 bg-gray-400 rounded-lg" uk-lightbox>
                                        <a href="{{ asset('uploads/products') . '/' . json_decode($product->images)[0] }}">
                                            <img src="{{ asset('uploads/products') . '/' . json_decode($product->images)[0] }}" alt="" class="sm:rounded-lg w-full h-full object-contain">
                                        </a>
                                    </div>
                                </div>
            
                                <!-- details -->
                                <div class="pt-2 w-full">
                                    <p class="text-md font-semibold line line-clamp-2 break-words">{{ $product->name }}</p>

                                    <div>
                                        {!! $product->getRatingStars() !!}
                                    </div>
                                    
                                    <p class="text-md">₱{{ $product->priceRange() }}</p>
            
                                    <div class="flex items-center justify-end w-full">
                                        <x-button white label="View" onclick="$openModal('productViewModal')" wire:click="$dispatch('view-product-info', { id: {{ $product->id }} })"/>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </li>
    </ul> 
</div>