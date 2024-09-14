<x-layouts.main-layout>
    <div class="w-full" x-data="{ tabSelected: 1 }">
        <div class="w-full max-w-[1000px] mx-auto px-3 h-full">
            {{-- <div class="w-full bg-white rounded-lg relative shadow">
                @if($user->id == Auth::id())
                    <div class="absolute top-2 right-2">
                        <x-dropdown>
                            <x-slot name="trigger">
                                <x-mini-button  rounded icon="cog-6-tooth" flat gray />
                            </x-slot>
    
                            <x-dropdown.item label="Profile" wire:click="$dispatch('getProfileData')" onclick="$openModal('editProfileFormModal')"/>
                            <x-dropdown.item label="Change Password" onclick="$openModal('updatePasswordModal')"/>
                        </x-dropdown>
                    </div>
                @endif

                <!-- Header -->
                <div class="flex gap-5 flex-col items-center p-8">

                    <!-- Profile Picture -->
                    <div class="relative h-28 w-28 md:h-40 md:w-40 rounded-full overflow-hidden border-[6px] bg-slate-400 border-gray-100 shrink-0"> 
                        @if($user->profilePicture() == null)
                            <div class="w-full h-full object-cover absolute bottom-10 right-1">
                                <i class="ri-user-3-fill ri-10x"></i>
                            </div>
                        @else
                            <img src="{{ asset('uploads') . '/' . $user->profilePicture() }}" class="w-full h-full absolute object-cover">
                        @endif
                    </div>
                
                    <div class="w-full">
                        <!-- Name -->
                        <h3 class="text-xl font-semibold text-black dark:text-white text-center">{{ $user->role == App\Enums\UserType::Store ? $user->storeInformation->name : $user->userInformation->fullname() }}</h3>
                            
                        <!-- Username -->
                        <p class="text-sm text-gray-500 font-normal text-center">{{ '@' . $user->username }}</p>
                        
                        <!-- Bio -->
                        <p class="text-sm md:font-normal font-light text-center mt-2">
                            @if($user->role == App\Enums\UserType::Store)
                                {{ $user->storeInformation->profile_bio }}
                            @else
                                {{ $user->userInformation->profile_bio }}
                            @endif
                        </p>
                
                        <div class="flex flex-col items-center justify-center gap-3 mt-5">

                            @if($user->role != App\Enums\UserType::Store)
                                <div class="pb-2">
                                    <p>Currently in <span class="font-semibold">{{ $user->userInformation->current_country }}</span></p>
                                </div>
                            @endif

                            <div class="flex items-center justify-center gap-5">
                                <!-- Total Post -->
                                <div class="text-center leading-snug">
                                    <p class="p-0 m-0">Posts</p>
                                    
                                    <h3 class="p-0 m-0 sm:text-xl font-bold text-black dark:text-white text-center">
                                        {{ count($user->posts) }}
                                    </h3>
                                </div>

                                <!-- Total Comments -->
                                <div class="text-center leading-snug">
                                    <p class="p-0 m-0">Comments</p>
                                    
                                    <h3 class="p-0 m-0 sm:text-xl font-bold text-black dark:text-white text-center">
                                        {{ $user->getTotalPostCommentsReceived() }}
                                    </h3>
                                </div>

                                <!-- Total Likes -->
                                <div class="text-center leading-snug">
                                    <p class="p-0 m-0">Likes</p>
                                    
                                    <h3 class="p-0 m-0 sm:text-xl font-bold text-black dark:text-white text-center">
                                        {{ $user->getTotalPostLikesReceived() }}
                                    </h3>
                                </div>
                            </div>

                            <!-- Options -->
                            @if($user->id != Auth::id())
                                <div class="flex items-center justify-center flex-row gap-3">
                                    <x-button icon="chat-bubble-oval-left" href="{{ route('message', $user->username) }}" label="Message" />
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                @if($user->role == App\Enums\UserType::Travelpreneur || $user->role == App\Enums\UserType::Store)
                    <div class="flex items-center justify-center">
                        <div class="cursor-pointer px-10 py-3 transition-all" x-bind:class="tabSelected == 1 ? 'border-b-2 border-gray-500 font-semibold' : ''" x-on:click="tabSelected = 1">Posts</div>
                        <div class="cursor-pointer px-10 py-3 transition-all" x-bind:class="tabSelected == 2 ? 'border-b-2 border-gray-500 font-semibold' : ''" x-on:click='tabSelected = 2'>Products</div>
                    </div>
                @endif
            </div> --}}

            <!-- Header -->
            <div class="w-full bg-white rounded-lg relative shadow">
                @if($user->id == Auth::id())
                    <div class="absolute top-2 right-2">
                        <x-dropdown>
                            <x-slot name="trigger">
                                <x-mini-button  rounded icon="cog-6-tooth" flat gray />
                            </x-slot>

                            <x-dropdown.item label="Profile" wire:click="$dispatch('getProfileData')" onclick="$openModal('editProfileFormModal')"/>
                            <x-dropdown.item label="Change Password" onclick="$openModal('updatePasswordModal')"/>
                        </x-dropdown>
                    </div>
                @endif

                <!-- Header -->
                <div class="grid grid-cols-12 p-5">
                    <!-- Left Side -->
                    <div class="col-span-12 lg:col-span-4 flex flex-col items-center justify-start gap-3">
                        <!-- Profile Picture -->
                        <div class="relative h-28 w-28 md:h-40 md:w-40 rounded-full overflow-hidden border-[6px] bg-slate-400 border-gray-100">
                            @if($user->profilePicture() == null)
                                <div class="w-full h-full object-cover absolute bottom-10 right-1">
                                    <i class="ri-user-3-fill ri-10x"></i>
                                </div>
                            @else
                                <img src="{{ asset('uploads') . '/' . $user->profilePicture() }}" class="w-full h-full absolute object-cover">
                            @endif
                        </div>
                        
                        <!-- User Location -->
                        @if($user->role != App\Enums\UserType::Store)
                            <div class="pb-2">
                                <p>Currently in <span class="font-semibold">{{ $user->userInformation->current_country }}</span></p>
                            </div>
                        @else
                            <div class="pb-2">
                                <p>Located at <span class="font-semibold">{{ $user->storeInformation->country }}</span></p>
                            </div>
                        @endif
                    </div>

                    <!-- Right Side -->
                    <div class="col-span-12 lg:col-span-8 flex flex-col just gap-3 flex-grow h-full">
                        <div class="flex flex-col justify-between h-full">
                            <!-- Name -->
                            <div class="flex max-lg:flex-col items-center lg:gap-3">
                                <h3 class="text-xl lg:text-3xl font-semibold text-black dark:text-white text-center">
                                    {{ $user->role == App\Enums\UserType::Store ? $user->storeInformation->name : $user->userInformation->fullname() }}
                                </h3>
                                <small class="text-gray-500 font-normal text-center lg:text-base">{{ '@' . $user->username }}</small>
                            </div>

                            <!-- Bio -->
                            <div class="flex-grow h-full">
                                <div class="text-sm mt-2 max-lg:text-center">
                                    @if($user->role == App\Enums\UserType::Store)
                                        @if($user->storeInformation->profile_bio)
                                            {{ $user->storeInformation->profile_bio  }}
                                        @else
                                            <p>We’re excited to have you here and look forward to offering you a great experience. Our goal is to provide you with excellent products and top-notch service.</p>
                                            <p class="mt-1">Feel free to browse our collection, and don’t hesitate to reach out if you have any questions or need assistance. Thank you for visiting, and we hope you enjoy what we have to offer!</p>
                                        @endif
                                    @else
                                        @if($user->userInformation->profile_bio)
                                            {{ $user->userInformation->profile_bio  }}
                                        @else
                                            <p>I’m new here and excited to be a part of this community! I’m looking forward to exploring and connecting with all of you. Whether it’s discovering new interests, sharing ideas, or just having a good conversation, I’m here for it.</p>
                                            <p class="mt-1">Feel free to say hi, share your favorite tips, or drop a recommendation. Let’s make the most of this journey together!</p>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-center">
                            <!-- Options -->
                            @if($user->id != Auth::id())
                                <div class="flex items-center justify-center gap-3">
                                    <x-button icon="chat-bubble-oval-left" href="{{ route('message', $user->username) }}" label="Message" />
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
         
                @if($user->role == App\Enums\UserType::Travelpreneur || $user->role == App\Enums\UserType::Store)
                    <div class="flex items-center justify-center">
                        <div class="cursor-pointer px-10 py-3 transition-all" x-bind:class="tabSelected == 1 ? 'border-b-2 border-gray-500 font-semibold' : ''" x-on:click="tabSelected = 1">Posts</div>
                        <div class="cursor-pointer px-10 py-3 transition-all" x-bind:class="tabSelected == 2 ? 'border-b-2 border-gray-500 font-semibold' : ''" x-on:click='tabSelected = 2'>Products</div>
                    </div>
                @endif
            </div>

            @if($user->role != App\Enums\UserType::Store && !empty($user->getTravelledCountry()))
                <div class="uk-position-relative uk-visible-toggle uk-light flex items-center justify-center flex-col bg-white mt-5 rounded-lg shadow p-5" tabindex="-1" uk-slider="finite: true;">
                    <p class="pb-3 text-lg text-slate-700">My Travels</p>

                    <div class="uk-slider-items uk-width-auto">
                        @foreach ($user->getTravelledCountry() as $country)
                            <x-button flat lg label="{{ $country }}" onclick="$openModal('viewTravel')" @click="$dispatch('get-travel-info', { country: '{{ $country }}', userId: {{ $user->id }} })">
                                <x-slot name="prepend">
                                    <img width="35" height="35" class="object-fit" src="{{ 'https://flagsapi.com/' . App\Classes\Location::getCountryCode($country) . '/flat/64.png' }}" alt="flag">
                                </x-slot>
                            </x-button>
                        @endforeach
                    </div>

                    <a class="uk-position-center-left uk-position-small uk-hidden-hover" href uk-slidenav-previous uk-slider-item="previous"></a>
                    <a class="uk-position-center-right uk-position-small uk-hidden-hover" href uk-slidenav-next uk-slider-item="next"></a>
                </div>
            @endif

            <!-- Content -->
            <div class="w-full flex flex-col items-center justify-center">
                <div x-show='tabSelected == 1' x-cloak x-transition class="w-[510px] max-w-[510px] min-h-screen rounded-lg max-sm:px-7 space-y-5 mt-5"> <!-- Posts -->
                    @if($user->id == Auth::id())
                        <div class="border w-full bg-white rounded-lg p-4 gap-3 shadow-sm flex justify-stretch items-stretch hover:cursor-pointer active:scale-95 transition-all" onclick="$openModal('postFormModal')">
                            <div class="w-full text-center bg-gray-200 rounded-lg py-2 font-medium text-sm select-none text-gray-600">What do you have in mind?</div>
                        
                            <i class="py-1 px-2 text-xl bg-blue-200 text-blue-800 rounded-lg">
                                <x-icon name="photo" class="w-full h-full" />
                            </i>
                        </div>
                    @endif
        
                    <!-- Posts Container -->
                    <livewire:Posting.posts-container userID="{{ $user->id }}"/>
                </div>
                @if($user->role == App\Enums\UserType::Travelpreneur || $user->role == App\Enums\UserType::Store)
                    <div x-show='tabSelected == 2' class="w-full px-5" x-cloak x-transition class="">
                        <div class="pt-5">
                            <div class="flex max-sm:items-start items-center max-sm:flex-col justify-between gap-4">
                                <p class="text-2xl font-semibold">Our Products</p>
                    
                                <div class="flex items-center justify-end max-md:w-full sm:w-7/12 md:w-5/12">
                                    <x-input icon="magnifying-glass" wire:model.live.debounce.200ms="search" placeholder="Search" shadowless />
                                </div>
                            </div>

                            <!-- Container here -->
                            <livewire:Product.products-container userID="{{ $user->id }}"/>
                        </div>
                    </div>
                @endif
            </div> 
        </div>
    </div>

    <livewire:Posting.post-form-modal />
    <livewire:Profile.edit-profile-form-modal />
    <livewire:TravelProfile.view-travel-modal />
    <livewire:Auth.change-password-modal />
</x-layouts.main-layout>    