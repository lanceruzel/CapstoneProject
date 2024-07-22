<x-layouts.main-layout wire:ignore.self>
    <div class="flex flex-col lg:flex-row lg:items-start items-center justify-center lg:gap-7 xl:gap-10" id="js-oversized" x-data="{ tabSelected: 1 }">
        <div class="flex flex-col">
            <div class="flex items-center justify-center pb-3">
                <div class="cursor-pointer px-10 py-3 transition-all" x-bind:class="tabSelected == 1 ? 'border-b-2 border-gray-500 font-semibold' : ''" x-on:click="tabSelected = 1">Status</div>
                <div class="cursor-pointer px-10 py-3 transition-all" x-bind:class="tabSelected == 2 ? 'border-b-2 border-gray-500 font-semibold' : ''" x-on:click='tabSelected = 2'>Livestreams</div>
            </div>
            
            <div class="w-[510px] max-w-[510px] min-h-screen rounded-lg max-sm:px-7 space-y-5" x-show='tabSelected == 1' x-cloak x-transition>
                <div class="border w-full bg-white rounded-lg p-4 gap-3 shadow-sm flex justify-stretch items-stretch hover:cursor-pointer active:scale-95 transition-all" onclick="$openModal('postFormModal')">
                    <div class="w-full text-center bg-gray-200 rounded-lg py-2 font-medium text-sm select-none text-gray-600">What do you have in mind?</div>
                
                    <i class="py-1 px-2 text-xl bg-blue-200 text-blue-800 rounded-lg">
                        <x-icon name="photo" class="w-full h-full" />
                    </i>
    
                    {{-- <i class="py-1 px-2 text-xl bg-rose-200 text-rose-800 rounded-lg">
                        <x-icon name="video-camera" class="w-full h-full" />
                    </i> --}}
                </div>
    
                <!-- Posts Container -->
                <livewire:Posting.posts-container />
            </div>

            <div class="w-[510px] max-w-[510px] min-h-screen rounded-lg max-sm:px-7 space-y-5" x-show='tabSelected == 2' x-cloak x-transition>
                <div class="border w-full bg-white rounded-lg p-4 gap-3 shadow-sm flex justify-stretch items-stretch hover:cursor-pointer active:scale-95 transition-all" onclick="$openModal('livestreamFormModal')">
                    <div class="w-full text-center bg-gray-200 rounded-lg py-2 font-medium text-sm select-none text-gray-600">Start Livestream</div>
    
                    <i class="py-1 px-2 text-xl bg-rose-200 text-rose-800 rounded-lg">
                        <x-icon name="video-camera" class="w-full h-full" />
                    </i>
                </div>

                <!-- Livestreams Container -->
                <livewire:Livestream.livestream-posts-container />
            </div>
        </div>
        
    
        <!-- Rightbar -->
        <div class="lg:max-w-[370px] max-md:max-w-[510px] max-sm:px-7" style="z-index: 0">
            <div class="space-y-5 uk-sticky" uk-sticky="end: #js-oversized; offset: 40; media:992;">
                <livewire:Etc.people-you-might-know />
                <livewire:Etc.random-product-container />
            </div>
        </div>
    </div>

    <livewire:Posting.post-form-modal />
    <livewire:Livestream.livestream-form-modal />
</x-layouts.main-layout>