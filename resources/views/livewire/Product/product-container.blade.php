<div class="relative hover:no-underline hover:text-gray-700 border shadow-sm rounded-lg cursor-pointer flex-1 bg-white overflow-hidden">
    <div onclick="$openModal('productViewModal')" wire:click="$dispatch('view-product-info', { id: {{ $product->id }}, feedback: false })">
        <div class="h-[220px] rounded-t-lg border-b-2">
            <img src="{{ asset('uploads/products') . '/' . json_decode($product->images)[0] }}" class="block w-full h-full object-contain rounded-t-lg bg-white" alt="...">
        </div>
    
        <div class="px-3 py-2 space-y-1">
            <p class="line-clamp-2 break-words font-medium">{{ $product->name }}</p>
    
            <p class="text-sm">{{ $product->priceRange() }}</p>
    
            <div class="flex justify-between items-center">
                <div class="pe-2">
                    <span>{!! $product->getRatingStars() !!}</span> <span class="text-xs">({{ $product->getTotalRatings() }})</span>
                </div>
    
                <p class="text-xs"><span>{{ $product->getSoldCount() }}</span> Sold</p>
            </div>
    
            {{-- <div class="text-sm">
                <p class="text-xs">From <span>Paris</span></p>
            </div> --}}
        </div>
    </div>
    
    @if($product->status === App\Enums\Status::ForReview || $product->status === App\Enums\Status::ForReSubmission)
        <div>
            <div class="absolute rounded-lg top-0 w-full h-full bg-gray-500/95 p-5">
                <div class="w-full h-full flex flex-col items-center justify-center text-white text-center space-y-3">
                    <p class="text-lg line-clamp-4">{{ $product->name }} is still in validation process</p>
                    <small class="text-sm">Your product will only be available once validated.</small>
                </div>
            </div>
        </div>
    @elseif($product->status === App\Enums\Status::Unavailable)
        <div>
            <div class="absolute rounded-lg top-0 w-full h-full bg-gray-500/95 p-5">
                <div class="w-full h-full flex flex-col items-center justify-center text-white text-center space-y-3">
                    <p class="text-lg line-clamp-4">{{ $product->name }}</p>
                    <small class="text-sm">This product is currently unavailable.</small>
                </div>
            </div>
        </div>
    @elseif($product->status === App\Enums\Status::Suspended)
        <div>
            <div class="absolute rounded-lg top-0 w-full h-full bg-gray-500/95 p-5">
                <div class="w-full h-full flex flex-col items-center justify-center text-white text-center space-y-3">
                    <p class="text-lg line-clamp-4">{{ $product->name }}</p>
                    <small class="text-sm">This product is currently SUSPENDED.</small>
                </div>
            </div>
        </div>
    @endif
</div>