@php
    use App\Enums\UserType;
@endphp

<x-modal-card name="productViewModal" title="Product" width='6xl' align='center' x-cloak x-on:close="$dispatch('clearProductViewModalData')" blurless wire:ignore.self>  
    @if($product != null)
        <div class="max-w-[1100px] mx-auto max-lg:p-5 lg:p-7">
            <div class="grid grid-cols-2 gap-5">
                <div class="max-lg:col-span-2 lg:col-span-1">   
                    @if(count($images) > 1)
                        <div class="relative uk-visible-toggle uk-slideshow w-full" tabindex="-1" uk-slideshow="ratio: false; animation: push;finite: true; min-width: 100%; max-width: 100%; min-height: 500px; max-height: 500px">
                            <div class="uk-slideshow-items" uk-lightbox="">
                                @foreach($images as $image)
                                    <li class="sm:rounded-md" tabindex="-1" style="">
                                        <a href="{{ asset('uploads/products') . '/' . $image }}">
                                            <img src="{{ asset('uploads/products') . '/' . $image }}" class="h-full object-fit" alt="">
                                        </a>
                                    </li>
                                @endforeach
                            </div>
        
                            <!-- navigation -->
                            <button type="button" class="absolute -left-3 -translate-y-1/2 bg-gray-100/50 backdrop-blur-xl rounded-full top-1/2 grid w-8 h-7 place-items-center border" uk-slideshow-item="previous">
                                <i class="ri-arrow-left-s-line ri-lg"></i>
                            </button>
        
                            <button type="button" class="absolute -right-3 -translate-y-1/2 bg-gray-100/50 backdrop-blur-xl rounded-full top-1/2 grid w-8 h-7 place-items-center border uk-invisible" uk-slideshow-item="next">
                                <i class="ri-arrow-right-s-line ri-lg"></i>
                            </button>
                        </div>
                    @else
                        <div class="relative w-full h-full" uk-lightbox>
                            <a href="{{ asset('uploads/products') . '/' . $images[0] }}">
                                <img src="{{ asset('uploads/products') . '/' . $images[0] }}" alt="" class="sm:rounded-lg w-full h-full object-contain">
                            </a>
                        </div>
                    @endif
                </div>
            
                <div class="max-lg:col-span-2 lg:col-span-1 space-y-3">
                    <p class="text-2xl font-semibold">{{ $product->name }}</p>
            
                    <div class="flex items-center divide-x-2">
                        <div class="pe-3">
                            {!! $product->getRatingStars('lg') !!}
                        </div>
            
                        <p class="px-3">{{ $product->getAverageRate() }} Ratings 
                            <span class="text-sm">
                                ({{ $product->getTotalFeedback() }} Reviews)
                            </span>
                        </p>
                        
                        <p class="text-xs px-3"><span>{{ $product->getSoldCount() }}</span> Sold</p>
                    </div>
            
                    <div class="flex items-center justify-between">
                        <p class="text-xl">
                            {{ $product->formattedPriceRage() }}
                        </p>

                        
                        <div class="flex items-center justify-center text-sm">
                            <x-icon name="map-pin" class="w-5 h-5" />
                            <span>{{ $product->origin }}</span>
                        </div>
                    </div>
            
                    <div class="flex flex-col justify-star">
                        <p class="pb-2">Description: </p>
                        <div class="text-wrap min-h-[250px] max-h-[250px] overflow-hidden overflow-y-auto rounded-sm break-words whitespace-pre-wrap">{!! $product->description !!}</div>
                    </div>
                    
                    <div class="flex max-lg:justify-center lg:justify-end items-center gap-3">
                        @if(auth()->user()->role != UserType::Store)
                            @if(count($variations) > 1)
                                <x-button onclick="$openModal('variationSelectionModal')" wire:click="$dispatch('view-variations-info', { id: {{ $product->id }} })" label="Add to cart" />
                            @else
                                <x-button wire:loading.attr="disabled" wire:click="store_toCart" spinner="store_toCart" label="Add to cart" />
                            @endif
                        @endif
                    </div>
                </div>  
            </div>
    
            <div class="max-lg:p-5 lg:p-7 border rounded-lg mt-3 grid grid-cols-12 gap-3">
                <div class="col-span-12 md:col-span-6 lg:col-span-4 flex max-sm:flex-col items-center justify-center gap-3">
                    @if($product->seller->profilePicture() == null)
                        <x-icon name="user" solid class="w-20 h-20 bg-gray-200 rounded-full p-2 border" />
                    @else
                        <img src="{{ asset('uploads') . '/' . $product->seller->profilePicture() }}" class="h-20 w-20 object-cover rounded-full shadow">
                    @endif

                    <div class="flex flex-col items-start justify-center gap-1">
                        <p class="text-lg font-medium">{{ $product->seller->name() }}</p>
                        
                        <div>
                            <x-button flat label="Chat Now" href="{{ route('message', $product->seller->username) }}" />
                            <x-button outline label="View Shop" href="{{ route('profile', $product->seller->username) }}" />
                        </div>
                    </div>
                </div>

                <div class="col-span-12 md:col-span-6 lg:col-span-4 flex flex-col items-center justify-center gap-3">
                    <div class="flex items-center justify-between w-2/4">
                        <p class="text-gray-500">Ratings</p>
                        <p class="text-purple-500 font-semibold">{{ $product->seller->getTotalProductRatings() }}</p>
                    </div>

                    <div class="flex items-center justify-between w-2/4">
                        <p class="text-gray-500">Products</p>
                        <p class="text-purple-500 font-semibold">{{ $product->seller->products()->count() }}</p>
                    </div>
                </div>

                <div class="lg:hidden md:col-span-6"></div>

                <div class="col-span-12 md:col-span-6 lg:col-span-4 flex flex-col items-center justify-center gap-3">
                    <div class="flex items-center justify-between w-2/4">
                        <p class="text-gray-500">Joined</p>
                        <p class="text-purple-500 font-semibold">{{ date_format($product->seller->created_at, "M d, Y") }}</p>
                    </div>

                    <div class="flex items-center justify-between w-2/4">
                        <p class="text-gray-500">Posts</p>
                        <p class="text-purple-500 font-semibold">{{ $product->seller->posts()->count() }}</p>
                    </div>
                </div>
            </div>

            <!-- Ratings -->
            <div class="max-lg:p-5 lg:p-7 border rounded-lg mt-3">
                <div class="flex gap-5 divide-x-2 flex-wrap">
                    <p class="text-md font-medium">Product Ratings</p>
                    
                    <div class="flex flex-row max-sm:flex-col max-sm:gap-1 gap-3 px-5">
                        <div>
                            <span class="font-semibold">{{ $product->getAverageRate() }}</span> out of 5.0
                        </div>
                
                        <div class="pe-3">
                            {!! $product->getRatingStars() !!}
    
                            <span class="text-sm">
                                ({{ $product->getTotalFeedback() }} Reviews)
                            </span>
                        </div>
                    </div>
                </div>
    
                <div class="space-y-3 divide-y-2">
                    <livewire:Product.Feedback.feedbacks-container id="{{ $product->id }}" :key="'feedbacks-' . $product->id" :id="$product->id" />
                </div>
            </div>
        </div>
    @else
        <div class="flex items-center justify-center w-full">
            <div class="flex items-row items-center justify-center gap-3">
                <x-icon name='arrow-path' class="h-5 w-5 animate-spin"/>

                <span>
                    Fetching Data...
                </span>
            </div>
        </div>
    @endif
</x-modal-card>