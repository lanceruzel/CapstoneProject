<x-layouts.main-layout>
    <div class="flex flex-col lg:flex-row lg:items-start items-center justify-center lg:gap-5 xl:gap-7" x-on:click="$openModal('simpleModal')">

        <div class="w-[510px] max-w-[510px] min-h-screen rounded-lg max-sm:px-7 space-y-5">
            <div class="border w-full bg-white rounded-lg p-4 gap-3 shadow-sm flex justify-stretch items-stretch hover:cursor-pointer active:scale-95 transition-all">
                <div class="w-full text-center bg-gray-200 rounded-lg py-2 font-medium text-sm select-none text-gray-600">What do you have in mind?</div>
            
                <i class="py-1 px-2 text-xl bg-blue-200 text-blue-800 rounded-lg">
                    <x-icon name="photo" class="w-full h-full" />
                </i>

                <i class="py-1 px-2 text-xl bg-rose-200 text-rose-800 rounded-lg">
                    <x-icon name="video-camera" class="w-full h-full" />
                </i>
            </div>

            <!-- Posts Container -->
            <div class="w-full">
                <livewire:Posting.post-container />
            </div>
        </div>
    
        <!-- Rightbar -->
        <div class="lg:max-w-[370px] max-md:max-w-[510px] max-sm:px-7 shadow-sm border rounded-lg">
            <!-- People You might know -->
            <div class="xl:space-y-6 space-y-5">
                <div class="bg-white rounded-xl shadow-sm p-5 px-6 border1">

                    <!-- Header -->
                    <div class="flex justify-between items-center">
                        <h3 class="font-bold"> People You might know </h3>
                        
                        <x-mini-button lg rounded icon="arrow-path" flat primary interaction:solid />
                    </div>
                
                    <!-- Content -->
                    <div class="space-y-4 capitalize text-xs font-normal mt-5 mb-2 text-gray-500">
                        {{-- @if(count($users) > 0)
                            @foreach ($users as $user) --}}
                                <div class="flex items-center gap-3">
                                    <a href="profile.html">
                                        <img src="https://static.everypixel.com/ep-pixabay/0329/8099/0858/84037/3298099085884037069-head.png" alt="" class="bg-gray-200 rounded-full w-10 h-10">
                                    </a>
                
                                    <div class="flex-1">
                                        <a href="#">
                                            <h4 class="font-semibold text-sm text-gray-700 dark:text-white">Lance Ruzel C. Ambrocio</h4>
                                        </a>
                
                                        <div class="mt-0.5">Suggested For You</div>
                                    </div>
                                    <a href="#" class="hover:no-underline hover:text-gray-600 text-sm rounded-full py-1.5 px-4 font-semibold">
                                        Message
                                    </a>
                                </div>
                            {{-- @endforeach
                        @else
                            <div class="flex items-center justify-center">
                                There are currently no users
                            </div>
                        @endif --}}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-modal name="simpleModal">
        <x-card title="Consent Terms">
            <p>
                Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the
                industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type
                and scrambled it to make a type specimen book. It has survived not only five centuries, but also the
                leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s
                with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop
                publishing software like Aldus PageMaker including versions of Lorem Ipsum.
            </p>
     
            <x-slot name="footer" class="flex justify-end gap-x-4">
                <x-button flat label="Cancel" x-on:click="close" />
     
                <x-button primary label="I Agree" wire:click="agree" />
            </x-slot>
        </x-card>
    </x-modal>
</x-layouts.main-layout>