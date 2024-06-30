@php
    use App\Enums\UserType;
@endphp

<x-modal-card name="productViewModal" title="" width='LG' align='center' x-cloak x-on:close="$dispatch('clearProductViewModalData')" blurless wire:ignore.self>  
    {{-- @if($variations || $productUpdate) --}}
    <div class="max-w-[1100px] mx-auto max-lg:p-5 lg:p-7">
        @if($product != null)
            <div class="grid grid-cols-2 gap-5">
                <div class="max-lg:col-span-2 lg:col-span-1">   
                    @if(count($images) > 1)
                        <div class="relative uk-visible-toggle uk-slideshow w-full" tabindex="-1" uk-slideshow="animation: push;finite: true;min-height: 300; max-height: 350">
                            <ul class="uk-slideshow-items" uk-lightbox="" style="min-height: 350px;">
                                @foreach($images as $image)
                                    <li class="w-full sm:rounded-md" tabindex="-1" style="">
                                        <a href="{{ asset('uploads/products') . '/' . $image }}">
                                            <img src="{{ asset('uploads/products') . '/' . $image }}" class="w-full h-full object-contain inset-0" alt="">
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
        
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
            
                    <p class="text-xl font-semibold">
                        {{ $product->priceRange() }}
                    </p>
            
                    <div class="line-clamp-4 text-wrap"> {!! $product->description !!} </div>
    
                    <div> {{ 'Stocks Available: x' . $product->stocks }} </div>
                    <div> Seller: 
                        @if($product->seller->role == UserType::Travelpreneur)
                            {{ $product->seller->userInformation->first_name . ' ' . $product->seller->userInformation->fullname() }}
                        @elseif($product->seller->role == UserType::Store)
                            {{ $product->seller->storeInformation->name }}
                        @endif
                    </div>
    
                    @if(auth()->user()->role != UserType::Admin && auth()->user()->role != UserType::Store)
                        <div class="flex items-center gap-x-3" x-data="{ quantity: @entangle('quantity') }">
                            <div class="flex items-center gap-x-3" x-data="{
                                quantity: @entangle('quantity'),
                                plus() { 
                                    this.quantity++ 
                                },
                                minus() { 
                                    (quantity >= 2) ? this.quantity-- : this.quantity
                                }
                            }">
                                <x-button x-hold.click="minus" icon="minus" />
                             
                                <span class="bg-teal-600 text-white px-5 py-1.5 rounded-lg" x-text="quantity"></span>
                             
                                <x-button x-hold.click="plus" icon="plus" />
                            </div>
                        </div>
                
                        <div class="flex max-lg:justify-center lg:justify-end items-center gap-3">
                            @if(auth()->user()->role != UserType::Store)
                                <x-button wire:click="store_toCart" label="Add to cart" />
                            @endif
                        </div>
                    @endif
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
                    @if($product->seller->id != Auth::id() && auth()->user()->role != UserType::Admin)
                        @if($hasFeedback == false && $hasOrderedItem)
                            <div class="flex flex-col gap-3 py-5">
                                <div class="flex flex-row gap-3 w-full">
                                    <div class="min-w-14 min-h-14 size-14 rounded-full bg-red-500"></div>
                                    <livewire:Product.Feedback.feedback-form id="{{ $product->id }}">
                                </div>
                            </div>
                        @endif
                    @endif
    
                    <div>
                        <livewire:Product.Feedback.feedbacks-container id="{{ $product->id }}" :key="'feedbacks-' . $product->id" :id="$product->id" />
                    </div>
                </div>
            </div>
        @endif
    </div>
    {{-- @else
        <div class="flex items-center justify-center w-full">
            <div class="flex items-row items-center justify-center gap-3">
                <x-icon name='arrow-path' class="h-5 w-5 animate-spin"/>

                <span>
                    Fetching Data...
                </span>
            </div>
        </div>
    @endif --}}
</x-modal-card>