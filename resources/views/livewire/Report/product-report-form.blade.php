<x-modal-card name="productReportFormModal" title="Product Report" align='center' x-cloak x-on:close="$dispatch('clearProductReportFormModalData')" blurless wire:ignore.self>  
    <div class="flex flex-col gap-2 items-start text-gray-600 overflow-auto">
        <div class="flex flex-col gap-2 w-full">
            <x-select
                class="max-lg:col-span-2"
                label="Products:"
                wire:model="selectedProducts"
                placeholder="Select Product"
                :options="$orderedProducts"
                option-label="name"
                option-value="id"
                multiselect
                shadowless
            />
            
            <x-select label="Reason" wire:model='reason' placeholder="Please select an action" shadowless>
                <x-select.option label="Missing part of the order" value="Missing part of the order" />
                <x-select.option label="Seller sent wrong item" value="Seller sent wrong item" />
                <x-select.option label="Damaged Item" value="Damaged Item" />
                <x-select.option label="Product is defective or does not work" value="Product is defective or does not work" />
                <x-select.option label="Empty Parcel" value="Empty Parcel" />
                <x-select.option label="Others" value="Others" />
            </x-select>

            <x-textarea label="Description" wire:model="description" fluid shadowless placeholder="Type here" />

            <x-errors only="images" />
                
            @if($errors->has('images.*'))
                <x-alert title="Error!" negative>
                    <x-slot name="slot">
                        <ul>
                            @foreach ($errors->get('images.*') as $error)
                                <li>{{ $error[0] }}</li>
                            @endforeach
                        </ul>
                    </x-slot>
                </x-alert>
            @endif

            @if($images)
                <div class="max-w-full flex gap-4 overflow-x-auto p-3 pt-5" uk-lightbox>
                    @foreach($images as $key => $image)
                        <div class="flex-shrink-0 w-56 h-56 relative">
                            <a href="{{ is_object($image) && method_exists($image, 'temporaryUrl') ? $image->temporaryUrl() : asset('uploads/reports') . '/' . $image }}">
                                <img src="{{ is_object($image) && method_exists($image, 'temporaryUrl') ? $image->temporaryUrl() : asset('uploads/reports') . '/' . $image }}" alt="Uploaded Image" accept="image/png, image/jpeg" class="w-full h-full object-cover rounded-lg shadow border">
                            </a>

                            <button wire:click="deleteImage({{ $key }})" class="absolute -top-5 -right-3.5 active:scale-95 transition-all">
                                <x-icon name="x-circle" solid class="w-8 h-8" />
                            </button>
                        </div>  
                    @endforeach
                </div>
            @endif

            <div class="w-full mt-3 border rounded-lg bg-[url('https://demo.foxthemes.net/instello/assets/images/ad_pattern.png')] bg-repeat">   
                <label wire:target='images' wire:loading.class="pointer-events-none" class="py-5 flex flex-col justify-center items-center cursor-pointer relative">
                    <input class="hidden" type="file" accept="image/png, image/jpg, image/jpeg" multiple wire:model="images">
                    
                    <div class="flex flex-col items-center justify-center" wire:target='images' wire:loading.remove>
                        <x-icon name="photo" class="w-10 h-10 text-teal-600" lg />
                        <span class="text-gray-700 mt-2">Browse to Upload image</span>
                    </div>
                    
                    <div wire:target='images' wire:loading class="flex flex-col gap-3 items-center justify-center">
                        <div class="flex items-center justify-center w-full">
                            <svg class="animate-spin h-10 w-10 text-teal-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </div>
                        
                        <p class="text-gray-700">Loading previews...</p>
                    </div>
                </label>
            </div>      
        </div>
        
        <x-slot name="footer" class="flex justify-end gap-x-4">
            <x-button wire:loading.attr="disabled" flat label="Cancel" x-on:click="close" />
            <x-button wire:loading.attr="disabled" wire:click="send" spinner="send" label="Submit" />
        </x-slot>
    </div>
</x-modal-card>