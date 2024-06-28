<x-modal-card name="productRegistrationModal" title="Product Information" align='center' x-cloak x-on:close="$dispatch('clearProductFormModalData')" blurless wire:ignore.self>  
    @if($variations)
        <div class="flex flex-col gap-2 items-start text-gray-600 overflow-auto">
            <div class="flex flex-col gap-2 w-full">

                <x-input disabled label="Product Name" wire:model="name" shadowless />
                <x-textarea disabled label="Description" wire:model="description" placeholder="Write product's description here." />
                <x-select disabled label="Categories" wire:model="category" placeholder="Select Category" :options="$categories" option-label="name" option-value="name" shadowless />

                <x-checkbox disabled class="m-2" label="Enable Variation" wire:model.live="hasVariation" lg/>

                @if($hasVariation)
                    <table class="w-full border-spacing-2 text-sm text-left">
                        <thead class="border-b-2">
                            <tr>
                                <th scope="col" class="px-6 py-3"></th>
                                <th scope="col" class="px-6 py-3">Variation Name</th>
                                <th scope="col" class="px-6 py-3">Stock</th>
                                <th scope="col" class="px-6 py-3">Price</th>
                            </tr>
                        </thead>
            
                        <tbody>
                            @foreach ($variations as $key => $variation)
                                <tr wire:key="{{ $key }}">
                                    <td class="px-2">
                                        @if(count($variations) > 2)
                                            <x-mini-button disabled rounded icon="trash" flat gray interaction="negative" wire:click="removeVariation('{{ $key }}')" />
                                        @endif
                                    </td>

                                    <td class="py-3">
                                        <x-input disabled class="w-[140px]" label="" wire:model="variations.{{ $key }}.name" placeholder="Eg. Small" shadowless />
                                    </td>

                                    <td class="py-3">
                                        <x-maskable disabled class="w-[100px]" label="" icon="truck" mask="####" wire:model="variations.{{ $key }}.stocks" shadowless />
                                    </td>

                                    <td class="py-3">
                                        <x-currency disabled class="w-[110px]" label="" prefix="$" thousands="," decimal="." wire:model="variations.{{ $key }}.price" shadowless />
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>

                        <caption class="caption-bottom">
                            {{-- <x-button icon='plus' flat black label="Add Variation" wire:click="addVariation" /> --}}
                        </caption>
                    </table>
                @else
                    <div class="grid grid-cols-2 gap-3">
                        <x-maskable disabled class="max-lg:col-span-2" label="Stock" icon="truck" mask="####" wire:model="stocks" shadowless />
                        <x-currency disabled class="max-lg:col-span-2" label="Price" prefix="$" thousands="," decimal="." wire:model="price" shadowless />
                    </div>
                @endif
                
                <x-errors only="images.*" />

                @if($images)
                    <div class="max-w-full flex gap-4 overflow-x-auto p-3 pt-5" uk-lightbox>
                        @foreach($images as $key => $image)
                            <div class="flex-shrink-0 w-56 h-56 relative">
                                <a href="{{ is_object($image) && method_exists($image, 'temporaryUrl') ? $image->temporaryUrl() : asset('uploads/products') . '/' . $image }}">
                                    <img src="{{ is_object($image) && method_exists($image, 'temporaryUrl') ? $image->temporaryUrl() : asset('uploads/products') . '/' . $image }}" alt="Uploaded Image" accept="image/png, image/jpeg" class="w-full h-full object-cover rounded-lg shadow border">
                                </a>

                                {{-- <button wire:click="deleteImage({{ $key }})" class="absolute -top-5 -right-3.5 active:scale-95 transition-all">
                                    <x-icon name="x-circle" solid class="w-8 h-8" />
                                </button> --}}
                            </div>  
                        @endforeach
                    </div>
                @endif   

                @if($product->status == App\Enums\Status::ForReSubmission || $product->status == App\Enums\Status::ForReview)
                    <x-textarea label="Admin's Remarks" wire:model="remarks" placeholder="Type here..." />
                @else
                    <x-textarea disabled label="Admin's Remarks" wire:model="remarks" placeholder="Type here..." />
                @endif
            </div>
            
            <x-slot name="footer" class="flex justify-between gap-x-4">
                <x-button flat label="Close" x-on:click="close" />

                @if($product->status == App\Enums\Status::ForReSubmission || $product->status == App\Enums\Status::ForReview)
                    <div>
                        <x-button wire:loading.attr="disabled" wire:click="store('resubmit')" negative spinner="store" label="Decline" />
                        <x-button wire:loading.attr="disabled" wire:click="store('accepted')" positive spinner="store" label="Accept" />
                    </div>
                @endif
            </x-slot>
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