<x-layouts.main-layout>
    <div class="flex flex-col lg:flex-row lg:items-start items-center justify-center lg:gap-5 xl:gap-7">
        <div class="w-[510px] max-w-[510px] min-h-screen rounded-lg max-sm:px-7 space-y-5">
            <div class="border w-full bg-white rounded-lg p-4 gap-3 shadow-sm flex justify-stretch items-stretch hover:cursor-pointer active:scale-95 transition-all" onclick="$openModal('postFormModal')">
                <div class="w-full text-center bg-gray-200 rounded-lg py-2 font-medium text-sm select-none text-gray-600">What do you have in mind?</div>
            
                <i class="py-1 px-2 text-xl bg-blue-200 text-blue-800 rounded-lg">
                    <x-icon name="photo" class="w-full h-full" />
                </i>

                <i class="py-1 px-2 text-xl bg-rose-200 text-rose-800 rounded-lg">
                    <x-icon name="video-camera" class="w-full h-full" />
                </i>
            </div>

            <!-- Posts Container -->
            <livewire:Posting.posts-container />
        </div>
    
        <!-- Rightbar -->
        <div class="lg:max-w-[370px] max-md:max-w-[510px] max-sm:px-7 shadow-sm border rounded-lg">
            <!-- People You might know -->
            <livewire:Etc.people-you-might-know />
        </div>
    </div>

    <livewire:Posting.post-form-modal />
</x-layouts.main-layout>