<x-layouts.main-layout>
    <div class="w-full">
        <div class="w-full max-w-[1000px] mx-auto px-3 min-h-screen">
            <div class="w-full border-2 bg-white rounded-lg">
                <!-- Header -->
                <div class="flex gap-5 flex-col items-center p-8">

                    <!-- Profile Picture -->
                    <div class="relative h-28 w-28 md:h-40 md:w-40 rounded-full overflow-hidden border-[6px] bg-slate-400 border-gray-100 shrink-0"> 
                        {{-- @if($account->userProfile->profile_dp == null) --}}
                            <div class="w-full h-full object-cover absolute bottom-10 right-1">
                                <i class="ri-user-3-fill ri-10x"></i>
                            </div>
                        {{-- @else
                            <img src="{{ asset('uploads/all') . '/' . $account->userProfile->profile_dp }}" class="w-full h-full absolute object-cover">
                        @endif --}}
                    </div>
                
                    <div class="w-full">
                        <!-- Name -->
                        <h3 class="text-xl font-semibold text-black dark:text-white text-center">{{ $user->userInformation->fullname() }}</h3>
                            
                        <!-- Username -->
                        <p class="text-sm text-gray-500 font-normal text-center">{{ '@' . $user->username }}</p>
                        
                        <!-- Bio -->
                        <p class="text-sm md:font-normal font-light text-center mt-2">
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Omnis iure vel magni repudiandae tempora voluptatum rem mollitia ab quo fuga ea sint, consequatur sed praesentium vitae! Animi molestias laboriosam minima.
                        </p>
                
                        <div class="flex flex-col justify-center gap-3 mt-5">

                            <!-- Total Post -->
                            <div class="text-center leading-snug">
                                <p class="p-0 m-0">Posts</p>
                                
                                <h3 class="p-0 m-0 sm:text-xl font-bold text-black dark:text-white text-center">
                                    4
                                </h3>
                            </div>

                            <!-- Options -->
                            <div class="flex items-center justify-center flex-row gap-3">
                                @if($user->id == Auth::id())
                                   <x-button icon="user" label="Update Profile" />
                                @else
                                    <x-button icon="chat-bubble-oval-left" label="Message" />
                                @endif
                            </div>
                        </div>
                    </div>
                </div> 
            </div>

            <!-- Content -->
            <div class="w-full flex items-center justify-center">
                <div class="w-[510px] max-w-[510px] min-h-screen rounded-lg max-sm:px-7 space-y-5 mt-5">
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
                    <livewire:Posting.posts-container userID="{{ $user->id }}"/>
                </div>
            </div>
            
        </div>
    </div>

    <livewire:Posting.post-form-modal />
</x-layouts.main-layout>