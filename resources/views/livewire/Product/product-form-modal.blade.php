<x-modal-card name="productFormModal" title="Manage Product" align='center' x-cloak x-on:close="$dispatch('clearProductFormModalData')" blurless wire:ignore.self>  
    {{-- @if($variations || $productUpdate) --}}
        <div class="flex flex-col gap-2 items-start text-gray-600 overflow-auto">
            <div class="flex flex-col gap-2 w-full">

                @if($productUpdate && $productUpdate->status == App\Enums\Status::ForReSubmission && $productUpdate->remarks)
                    <x-alert title="Admin's Remark" info>
                        <x-slot name="slot">
                            {{ $productUpdate->remarks }}
                        </x-slot>
                    </x-alert>
                @endif

                <x-input label="Product Name" wire:model="name" shadowless />
                <x-textarea label="Description" wire:model="description" placeholder="Write product's description here." />
                <x-select label="Categories" wire:model="category" placeholder="Select Category" :options="$categories" option-label="name" option-value="name" shadowless />

                <x-checkbox class="m-2" label="Enable Variation" wire:model.live="hasVariation" lg/>

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
                                            <x-mini-button rounded icon="trash" flat gray interaction="negative" wire:click="removeVariation('{{ $key }}')" />
                                        @endif
                                    </td>

                                    <td class="py-3">
                                        <x-input class="w-[140px]" label="" wire:model="variations.{{ $key }}.name" placeholder="Eg. Small" shadowless />
                                    </td>

                                    <td class="py-3">
                                        <x-maskable class="w-[100px]" label="" icon="truck" mask="####" wire:model="variations.{{ $key }}.stocks" shadowless />
                                    </td>

                                    <td class="py-3">
                                        <x-currency class="w-[110px]" label="" prefix="$" thousands="," decimal="." wire:model="variations.{{ $key }}.price" shadowless />
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>

                        <caption class="caption-bottom">
                            <x-button icon='plus' flat black label="Add Variation" wire:click="addVariation" />
                        </caption>
                    </table>
                @else
                    <div class="grid grid-cols-2 gap-3">
                        <x-maskable class="max-lg:col-span-2" label="Stock" icon="truck" mask="####" wire:model="stocks" shadowless />
                        <x-currency class="max-lg:col-span-2" label="Price" prefix="$" thousands="," decimal="." wire:model="price" shadowless />
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

                                <button wire:click="deleteImage({{ $key }})" class="absolute -top-5 -right-3.5 active:scale-95 transition-all">
                                    <x-icon name="x-circle" solid class="w-8 h-8" />
                                </button>
                            </div>  
                        @endforeach
                    </div>
                @endif

                <div class="w-full mt-3 border1 rounded-lg bg-[url('https://demo.foxthemes.net/instello/assets/images/ad_pattern.png')] bg-repeat">   
                    <label wire:target='images' wire:loading.attr='disabled' class="py-5 flex flex-col justify-center items-center cursor-pointer">
                        <input class="hidden" type="file" accept="image/png, image/jpg, image/jpeg" multiple wire:model="images">
                        <x-icon name="photo" class="w-10 h-10 text-teal-600" lg />
                        
                        <span wire:target='images' wire:loading.remove class="text-gray-700 mt-2">Browse to Upload image</span>
                        <span wire:target='images' wire:loading class="text-gray-700 mt-2">Loading previews...</span>
                    </label>
                </div>    
            </div>
            
            <x-slot name="footer" class="flex justify-end gap-x-4">
                <x-button flat label="Cancel" x-on:click="close" />

                @if($productUpdate)
                    <x-button wire:loading.attr="disabled" wire:click="store" spinner="store" label="Update" />
                @else
                    <x-button wire:loading.attr="disabled" wire:click="store" spinner="store" label="Publish" />
                @endif
                
            </x-slot>
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