<x-modal-card name="postFormModal" title="Manage Post" align='center' blurless wire:ignore.self>
    <div class="flex flex-col gap-2 items-start text-gray-600">
        <div class="flex flex-col gap-2 w-full">
            <div class="flex justify-between items-center">
                <p class="font-semibold">What do you have in mind?</p>
            </div>

            <label class="w-full">
                <textarea wire:model="content" rows="5" class="w-full bg-gray-50 text-sm font-medium rounded-lg border focus:outline-none focus:ring-0 focus:border-gray-400 p-3 resize-none {{$errors->has('content') ? 'border-red-500' : 'border-gray-100'}}" placeholder="Type here" style="resize: vertical"></textarea>
                
                @if($errors->has('content'))
                    <span class="text-red-500">{{ $errors->first('content') }}</span>
                @endif  
            </label>

            <x-errors only="images" />

            @if($images)
                <div class="max-w-[520px] flex gap-4 overflow-x-auto p-3 pt-5" uk-lightbox>
                    @foreach($images as $key => $image)
                        <div class="flex-shrink-0 w-56 h-56 relative">
                            <a href="{{ is_object($image) && method_exists($image, 'temporaryUrl') ? $image->temporaryUrl() : asset('uploads/posts') . '/' . $image }}">
                                <img src="{{ is_object($image) && method_exists($image, 'temporaryUrl') ? $image->temporaryUrl() : asset('uploads/posts') . '/' . $image }}" alt="Uploaded Image" accept="image/png, image/jpeg" class="w-full h-full object-cover rounded-lg shadow border">
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
                    <input class="hidden" type="file" multiple wire:model="images">
                    <x-icon name="photo" class="w-10 h-10 text-teal-600" lg />
                    
                    <span wire:target='images' wire:loading.remove class="text-gray-700 mt-2">Browse to Upload image</span>
                    <span wire:target='images' wire:loading class="text-gray-700 mt-2">Loading previews...</span>
                </label>
            </div>    
        </div>
        
        <x-slot name="footer" class="flex justify-end gap-x-4">
            <x-button flat label="Cancel" x-on:click="close" />

            @if($postUpdate)
                <x-button wire:loading.attr="disabled" wire:click="store" spinner="store" label="Update" />
            @else
                <x-button wire:loading.attr="disabled" wire:click="store" spinner="store" label="Post" />
            @endif
            
        </x-slot>
    </div>
</x-modal-card>