<x-modal-card name="postFormModal" title="Manage Post" align='center' x-cloak x-on:close="$dispatch('clearPostFormModalData')" blurless wire:ignore.self>
    <div class="flex flex-col gap-2 items-start text-gray-600">
        <div class="flex flex-col gap-2 w-full">
            <div class="flex justify-between items-center">
                <p class="font-semibold">What do you have in mind?</p>
                <x-checkbox label="Include in compilation" wire:model='isIncluded' />
            </div>

            <label class="w-full">
                <textarea wire:model="content" rows="5" class="w-full bg-gray-50 text-sm font-medium rounded-lg border focus:outline-none focus:ring-0 focus:border-gray-400 p-3 resize-none {{$errors->has('content') ? 'border-red-500' : 'border-gray-100'}}" placeholder="Type here" style="resize: vertical"></textarea>
                
                @error('content')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </label>

            <x-errors only="media" />

            <div class="w-full mt-3">
                <div class="border rounded-lg bg-[url('https://demo.foxthemes.net/instello/assets/images/ad_pattern.png')] bg-repeat"
                    x-data="{ 
                        uploading: false, 
                        progress: 0,
                        dragOver: false,
                        handleFiles(event) {
                            const files = event.dataTransfer.files;
                            if (files.length) {
                                this.$refs.fileInput.files = files;  // Assign files to the input
                                this.$refs.fileInput.dispatchEvent(new Event('change'));  // Trigger change event
                            }
                        }
                    }"
                    x-on:livewire-upload-start="uploading = true"
                    x-on:livewire-upload-finish="uploading = false; progress = 0"
                    x-on:livewire-upload-error="uploading = false"
                    x-on:livewire-upload-progress="progress = $event.detail.progress"
                    x-on:dragover.prevent="dragOver = true"
                    x-on:dragleave.prevent="dragOver = false"
                    x-on:drop.prevent="dragOver = false; handleFiles($event)"
                >   
                    <label wire:target='media' wire:loading.class="pointer-events-none" x-bind:class="{ 'border-2 border-dashed border-blue-400': dragOver }" class="py-8 flex flex-col justify-center items-center cursor-pointer relative transition-all duration-300 ease-in-out">
                        <input x-ref="fileInput" class="hidden" type="file" multiple wire:model="media">
                        
                        <div class="flex flex-col items-center justify-center" wire:target='media' wire:loading.remove>
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-teal-600 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <p class="text-gray-700 font-medium">Drag and drop or click to upload images or videos</p>
                            <p class="text-sm text-gray-500 mt-1">Supports PNG, JPG, JPEG, MP4, MOV, AVI</p>
                        </div>
                        
                        <div wire:target='media' wire:loading class="flex flex-col gap-3 items-center justify-center">
                            <div x-show="uploading" class="w-full max-w-xs">
                                <div class="bg-gray-400 rounded-full h-4 dark:bg-gray-700 w-full relative">
                                    <div class="bg-teal-600 h-4 rounded-full" x-bind:style="{ width: `${progress}%` }"></div>
                                    
                                    <div class="absolute inset-0 flex justify-center items-center">
                                        <span class="text-xs text-white font-semibold" x-text="`${progress}%`"></span>
                                    </div>
                                </div>
                            </div>
                            
                            <p class="text-gray-700">Uploading media...</p>
                        </div>
                    </label>
                </div>
            
                <div class="mt-4 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                    @if($media)
                        @foreach($media as $index => $item)
                            <div class="relative group" wire:key="post-{{ $index }}">
                                @if($this->identifyFileType(is_object($item) && method_exists($item, 'temporaryUrl') ? $item->temporaryUrl() : $item) == 'video')
                                    <video src="{{ is_object($item) && method_exists($item, 'temporaryUrl') ? $item->temporaryUrl() : asset('uploads/posts') . '/' . $item }}" alt="video preview" class="w-full h-32 object-cover rounded-lg shadow-md"></video>
                                @else
                                    <img src="{{ is_object($item) && method_exists($item, 'temporaryUrl') ? $item->temporaryUrl() : asset('uploads/posts') . '/' . $item }}" alt="image preview" class="w-full h-32 object-cover rounded-lg shadow-md">
                                @endif
                
                                <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-200 rounded-lg">
                                    <button wire:click="deleteMedia({{ $index }})" class="bg-red-500 text-white p-2 rounded-full hover:bg-red-600 transition-colors duration-200">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>            
        </div>
        
        <x-slot name="footer" class="flex justify-end gap-x-4">
            <x-button wire:loading.attr="disabled" flat label="Cancel" x-on:click="close" />

            @if($postUpdate)
                <x-button wire:loading.attr="disabled" wire:click="store" spinner="store" label="Update" />
            @else
                <x-button wire:loading.attr="disabled" wire:click="store" spinner="store" label="Post" />
            @endif
        </x-slot>
    </div>
</x-modal-card>