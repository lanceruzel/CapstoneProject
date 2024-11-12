<div>
    @if($conversation)
        @if($isAppeal == false)
            <!-- chat heading -->
            <div class="flex items-center justify-between gap-2 w- px-6 py-3.5 z-10 border-b dark:border-slate-700 uk-animation-slide-top-medium">
                <div class="flex items-center sm:gap-4 gap-2">

                    <!-- toggle for mobile -->
                    <x-mini-button white rounded icon="chevron-left" class="md:hidden" uk-toggle="target: #side-chat ; cls: max-md:-translate-x-full" aria-expanded="false" />
                        
                    <div class="relative cursor-pointer max-md:hidden h-10 w-10">
                        @if($conversation->user1->id != Auth::id())
                            @if($conversation->user1->profilePicture() == null)
                                <x-icon name="user" solid class="w-full h-full bg-gray-200 rounded-full p-2 border" />
                            @else
                                <img src="{{ asset('uploads') . '/' . $conversation->user1->profilePicture() }}" class="w-full h-full object-cover rounded-full border">
                            @endif
                        @else
                            @if($conversation->user2->profilePicture() == null)
                                <x-icon name="user" solid class="w-full h-full bg-gray-200 rounded-full p-2 border" />
                            @else
                                <img src="{{ asset('uploads') . '/' . $conversation->user2->profilePicture() }}" class="w-full h-full object-cover rounded-full border">
                            @endif
                        @endif
                        {{-- <div class="w-2 h-2 bg-teal-500 rounded-full absolute right-0 bottom-0 m-px"></div> --}}
                    </div>
                    
                    <div class="cursor-pointer">
                        @if($conversation->user2->id != Auth::id())
                            <a href="{{ route('profile', $conversation->user2->username) }}" class="hover:no-underline hover:text-gray-700 text-base font-bold">{{  $conversation->user2->role == App\Enums\UserType::Store ? $conversation->user2->storeInformation->name : $conversation->user2->userInformation->fullname() }}</a>
                        @else
                            <a href="{{ route('profile', $conversation->user1->username) }}" class="hover:no-underline hover:text-gray-700 text-base font-bold">{{  $conversation->user1->role == App\Enums\UserType::Store ? $conversation->user1->storeInformation->name : $conversation->user1->userInformation->fullname() }}</a>
                        @endif
                        
                        {{-- <div class="text-xs text-green-500 font-semibold">Online</div> --}}
                    </div>

                </div> 
                

                <div class="flex items-center gap-2">
                    {{-- <button type="button" class="button__ico">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-6 h-6">
                            <path fill-rule="evenodd" d="M2 3.5A1.5 1.5 0 013.5 2h1.148a1.5 1.5 0 011.465 1.175l.716 3.223a1.5 1.5 0 01-1.052 1.767l-.933.267c-.41.117-.643.555-.48.95a11.542 11.542 0 006.254 6.254c.395.163.833-.07.95-.48l.267-.933a1.5 1.5 0 011.767-1.052l3.223.716A1.5 1.5 0 0118 15.352V16.5a1.5 1.5 0 01-1.5 1.5H15c-1.149 0-2.263-.15-3.326-.43A13.022 13.022 0 012.43 8.326 13.019 13.019 0 012 5V3.5z" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                    <button type="button" class="hover:bg-slate-100 p-1.5 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" d="M15.75 10.5l4.72-4.72a.75.75 0 011.28.53v11.38a.75.75 0 01-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 002.25-2.25v-9a2.25 2.25 0 00-2.25-2.25h-9A2.25 2.25 0 002.25 7.5v9a2.25 2.25 0 002.25 2.25z"></path>
                        </svg>
                    </button> 
                    <button type="button" class="hover:bg-slate-100 p-1.5 rounded-full" uk-toggle="target: .rightt ; cls: hidden" aria-expanded="true">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"></path>
                        </svg> 
                    </button>  --}}
                </div>

            </div>
        @endif
            
        @if($isAppeal == false)
            <!-- chats bubble -->
            @if($media)
                <div id="chat-container" class="w-full p-5 py-10 overflow-y-auto md:h-[calc(100dvh-375px)] h-[calc(100vh-320px)]">
            @else
                <div id="chat-container" class="w-full p-5 py-10 overflow-y-auto md:h-[calc(100dvh-210px)] h-[calc(100vh-220px)]">
            @endif

                @if($isAppeal == false)
                    <div class="py-10 text-center text-sm lg:pt-8">
                        <div class="w-24 h-24 rounded-full mx-auto mb-3">
                            @if($conversation->user1->id != Auth::id())
                                @if($conversation->user1->profilePicture() == null)
                                    <x-icon name="user" solid class="w-full h-full bg-gray-200 rounded-full p-2 border" />
                                @else
                                    <img src="{{ asset('uploads') . '/' . $conversation->user1->profilePicture() }}" class="w-full h-full object-cover rounded-full border">
                                @endif
                            @else
                                @if($conversation->user2->profilePicture() == null)
                                    <x-icon name="user" solid class="w-full h-full bg-gray-200 rounded-full p-2 border" />
                                @else
                                    <img src="{{ asset('uploads') . '/' . $conversation->user2->profilePicture() }}" class="w-full h-full object-cover rounded-full border">
                                @endif
                            @endif
                        </div>
                        
                        <div class="mt-8">
                            <div class="md:text-xl text-base font-medium text-black dark:text-white">
                                @if($conversation->user2->id != Auth::id())
                                    {{  $conversation->user2->role == App\Enums\UserType::Store ? $conversation->user2->storeInformation->name : $conversation->user2->userInformation->fullname() }}
                                @else
                                    {{  $conversation->user1->role == App\Enums\UserType::Store ? $conversation->user1->storeInformation->name : $conversation->user1->userInformation->fullname() }}
                                @endif
                            </div>
                            {{-- <div class="text-gray-500 text-sm   dark:text-white/80"> @Monroepark </div> --}}
                        </div>
                        <div class="mt-3.5">
                            <a href="{{ route('profile', $conversation->user2->username) }}" class="inline-block rounded-lg px-4 py-1.5 text-sm font-semibold bg-gray-100">View profile</a>
                        </div>
                    </div>
                @endif

                <div class="text-sm font-medium space-y-6">
                    @foreach ($conversation->messages as $message)
                        @if($message->user_id == Auth::id())
                            <!-- sent -->
                            <div class="flex flex-col gap-1 items-end">
                                {{-- <img src="https://i.pravatar.cc" alt="" class="w-5 h-5 rounded-full shadow"> --}}
                                <div class="px-4 py-2 rounded-[20px] max-w-sm bg-gradient-to-tr from-sky-500 to-blue-500 text-white shadow break-words text-wrap hyphens-auto space-y-3">
                                    <span>{{ $message->content }}</span>

                                    <!-- media -->
                                    @if(json_decode($message->media) != null)
                                        @if(count(json_decode($message->media)) === 1)
                                            <div class="rounded h-60 min-h-60 max-h-60" uk-lightbox>
                                                {{-- -------------------------------- --}}
                                                @if($this->identifyFileType(json_decode($message->media)[0]) == 'video')
                                                    <a data-type="video" class="relative" href="{{ asset('uploads/messages') . '/' . json_decode($message->media)[0] }}">
                                                        <video src="{{ asset('uploads/messages') . '/' . json_decode($message->media)[0] }}" class="sm:rounded-lg w-full h-full object-cover" alt="video"></video>
                                                        
                                                        <div class="absolute top-0 bottom-0 right-0 left-0 flex items-center justify-center">
                                                            <x-icon name="play-circle" solid class="w-10 h-10" />
                                                        </div>
                                                    </a>
                                                @else
                                                    <a href="{{ asset('uploads/messages') . '/' . json_decode($message->media)[0] }}">
                                                        <img src="{{ asset('uploads/messages') . '/' . json_decode($message->media)[0] }}" class="sm:rounded-lg w-full h-full object-cover" alt="image">
                                                    </a>
                                                @endif
                                            </div>
                                        @elseif(count(json_decode($message->media)) > 1)
                                            @php
                                                $count = count(json_decode($message->media)); 
                                            @endphp
                                    
                                            <div class="grid {{ $count == 2 ? 'grid-cols-2' : 'grid-cols-3' }} gap-3">
                                                @foreach(json_decode($message->media) as $item)
                                                    <div class="col-span-1 rounded h-44 min-h-44 max-h-44" tabindex="-1" style="" uk-lightbox>
                                                        {{-- -------------------------------- --}}
                                                        @if($this->identifyFileType($item) == 'video')
                                                            <a data-type="video" class="relative" href="{{ asset('uploads/messages') . '/' . $item }}">
                                                                <video src="{{ asset('uploads/messages') . '/' . $item }}" class="sm:rounded-lg w-full h-full object-cover" alt="video"></video>
                                                                
                                                                <div class="absolute top-0 bottom-0 right-0 left-0 flex items-center justify-center">
                                                                    <x-icon name="play-circle" solid class="w-10 h-10" />
                                                                </div>
                                                            </a>
                                                        @else
                                                            <a href="{{ asset('uploads/messages') . '/' . $item }}">
                                                                <img src="{{ asset('uploads/messages') . '/' . $item }}" class="sm:rounded-lg w-full h-full object-cover" alt="image">
                                                            </a>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    @endif
                                </div>

                                <small>{{ date_format($message->created_at, "m/d/Y g:i A") }}</small> 
                            </div> 
                        @else
                            <!-- received -->
                            <div class="">
                                <p class="ms-12 pb-1 text-sm text-gray-500">
                                    @if($conversation->user2->id != Auth::id())
                                        {{  $conversation->user2->role == App\Enums\UserType::Store ? $conversation->user2->storeInformation->name : $conversation->user2->userInformation->fullname() }}
                                    @else
                                        {{  $conversation->user1->role == App\Enums\UserType::Store ? $conversation->user1->storeInformation->name : $conversation->user1->userInformation->fullname() }}
                                    @endif
                                </p>

                                <div class="flex gap-3">
                                    <div class="w-9 h-9 rounded-full shadow">
                                        @if($conversation->user1->id != Auth::id())
                                            @if($conversation->user1->profilePicture() == null)
                                                <x-icon name="user" solid class="w-full h-full bg-gray-200 rounded-full p-2 border" />
                                            @else
                                                <img src="{{ asset('uploads') . '/' . $conversation->user1->profilePicture() }}" class="w-full h-full object-cover rounded-full border">
                                            @endif
                                        @else
                                            @if($conversation->user2->profilePicture() == null)
                                                <x-icon name="user" solid class="w-full h-full bg-gray-200 rounded-full p-2 border" />    
                                            @else
                                                <img src="{{ asset('uploads') . '/' . $conversation->user2->profilePicture() }}" class="w-full h-full object-cover rounded-full border">
                                            @endif
                                        @endif
                                    </div>

                                    <div class="px-4 py-2 rounded-[20px] max-w-sm bg-gray-100 break-words !text-wrap hyphens-auto space-y-3">
                                        <span>{{ $message->content }}</span>

                                        <!-- media -->
                                        @if(json_decode($message->media) != null)
                                            @if(count(json_decode($message->media)) === 1)
                                                <div class="rounded h-60 min-h-60 max-h-60" uk-lightbox>
                                                    {{-- -------------------------------- --}}
                                                    @if($this->identifyFileType(json_decode($message->media)[0]) == 'video')
                                                        <a data-type="video" class="relative" href="{{ asset('uploads/messages') . '/' . json_decode($message->media)[0] }}">
                                                            <video src="{{ asset('uploads/messages') . '/' . json_decode($message->media)[0] }}" class="sm:rounded-lg w-full h-full object-cover" alt="video"></video>
                                                            
                                                            <div class="absolute top-0 bottom-0 right-0 left-0 flex items-center justify-center">
                                                                <x-icon name="play-circle" solid class="w-10 h-10" />
                                                            </div>
                                                        </a>
                                                    @else
                                                        <a href="{{ asset('uploads/messages') . '/' . json_decode($message->media)[0] }}">
                                                            <img src="{{ asset('uploads/messages') . '/' . json_decode($message->media)[0] }}" class="sm:rounded-lg w-full h-full object-cover" alt="image">
                                                        </a>
                                                    @endif
                                                </div>
                                            @elseif(count(json_decode($message->media)) > 1)
                                                @php
                                                    $count = count(json_decode($message->media)); 
                                                @endphp
                                        
                                                <div class="grid {{ $count == 2 ? 'grid-cols-2' : 'grid-cols-3' }} gap-3">
                                                    @foreach(json_decode($message->media) as $item)
                                                        <div class="col-span-1 rounded h-44 min-h-44 max-h-44" tabindex="-1" style="" uk-lightbox>
                                                            {{-- -------------------------------- --}}
                                                            @if($this->identifyFileType($item) == 'video')
                                                                <a data-type="video" class="relative" href="{{ asset('uploads/messages') . '/' . $item }}">
                                                                    <video src="{{ asset('uploads/messages') . '/' . $item }}" class="w-full h-full object-cover inset-0" alt="video"></video>
                                                                
                                                                    <div class="absolute top-0 bottom-0 right-0 left-0 flex items-center justify-center">
                                                                        <x-icon name="play-circle" solid class="w-10 h-10" />
                                                                    </div>
                                                                </a>
                                                            @else
                                                                <a href="{{ asset('uploads/messages') . '/' . $item }}">
                                                                    <img src="{{ asset('uploads/messages') . '/' . $item }}" class="w-full h-full object-cover inset-0" alt="image">
                                                                </a>
                                                            @endif
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                </div>

                                <div class="!ms-12">
                                    <small class="text-gray-400">{{ date_format($message->created_at, "m/d/Y g:i A") }}</small>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div> 
        @else
            <!-- chats bubble -->
            <div id="chat-container" class="!w-full py-3 overflow-y-auto h-[calc(100vh-350px)] md:h-[calc(100vh-137px)] ">
                <div class="text-sm font-medium space-y-6">
                    @foreach ($conversation->messages as $message)
                        @if($message->user_id == Auth::id())
                            <!-- sent -->
                            <div class="flex flex-col gap-1 items-end">
                                {{-- <img src="https://i.pravatar.cc" alt="" class="w-5 h-5 rounded-full shadow"> --}}
                                <div class="px-4 py-2 rounded-[20px] max-w-sm bg-gradient-to-tr from-sky-500 to-blue-500 text-white shadow break-words text-wrap hyphens-auto space-y-3">
                                    <span>{{ $message->content }}</span>

                                    <!-- media -->
                                    @if(json_decode($message->media) != null)
                                        @if(count(json_decode($message->media)) === 1)
                                            <div class="rounded h-60 min-h-60 max-h-60" uk-lightbox>
                                                {{-- -------------------------------- --}}
                                                @if($this->identifyFileType(json_decode($message->media)[0]) == 'video')
                                                    <a data-type="video" class="relative" href="{{ asset('uploads/messages') . '/' . json_decode($message->media)[0] }}">
                                                        <video src="{{ asset('uploads/messages') . '/' . json_decode($message->media)[0] }}" class="sm:rounded-lg w-full h-full object-cover" alt="video"></video>
                                                    
                                                        <div class="absolute top-0 bottom-0 right-0 left-0 flex items-center justify-center">
                                                            <x-icon name="play-circle" solid class="w-10 h-10" />
                                                        </div>
                                                    </a>
                                                @else
                                                    <a href="{{ asset('uploads/messages') . '/' . json_decode($message->media)[0] }}">
                                                        <img src="{{ asset('uploads/messages') . '/' . json_decode($message->media)[0] }}" class="sm:rounded-lg w-full h-full object-cover" alt="image">
                                                    </a>
                                                @endif

                                            </div>
                                        @elseif(count(json_decode($message->media)) > 1)
                                            @php
                                                $count = count(json_decode($message->media)); 
                                            @endphp
                                    
                                            <div class="grid {{ $count == 2 ? 'grid-cols-2' : 'grid-cols-3' }} gap-3">
                                                @foreach(json_decode($message->media) as $item)
                                                    <div class="col-span-1 rounded h-44 min-h-44 max-h-44" tabindex="-1" style="" uk-lightbox>
                                                        {{-- -------------------------------- --}}
                                                        @if($this->identifyFileType($item) == 'video')
                                                            <a data-type="video" class="relative" href="{{ asset('uploads/messages') . '/' . $item }}">
                                                                <video src="{{ asset('uploads/messages') . '/' . $item }}" class="w-full h-full object-cover inset-0" alt="video"></video>
                                                                
                                                                <div class="absolute top-0 bottom-0 right-0 left-0 flex items-center justify-center">
                                                                    <x-icon name="play-circle" solid class="w-10 h-10" />
                                                                </div>
                                                            </a>
                                                        @else
                                                            <a href="{{ asset('uploads/messages') . '/' . $item }}">
                                                                <img src="{{ asset('uploads/messages') . '/' . $item }}" class="w-full h-full object-cover inset-0" alt="image">
                                                            </a>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    @endif
                                </div>
                                <small>{{ date_format($message->created_at, "m/d/Y g:i A") }}</small> 
                            </div> 

                        @else
                            <!-- received -->
                            <div>
                                <div class="flex gap-3">
                                    <div class="w-9 h-9 rounded-full shadow">
                                        @if($conversation->user1->id != Auth::id())
                                            @if($conversation->user1->profilePicture() == null)
                                                <x-icon name="user" solid class="w-full h-full bg-gray-200 rounded-full p-2 border" />
                                            @else
                                                <img src="{{ asset('uploads') . '/' . $conversation->user1->profilePicture() }}" class="w-full h-full object-cover rounded-full border">
                                            @endif
                                        @else
                                            @if($conversation->user2->profilePicture() == null)
                                                <x-icon name="user" solid class="w-full h-full bg-gray-200 rounded-full p-2 border" />
                                            @else
                                                <img src="{{ asset('uploads') . '/' . $conversation->user2->profilePicture() }}" class="w-full h-full object-cover rounded-full border">
                                            @endif
                                        @endif
                                    </div>
                                    
                                    <div class="px-4 py-2 rounded-[20px] max-w-sm bg-gray-100 shadow break-words !text-wrap hyphens-auto space-y-3">
                                        <span>{{ $message->content }}</span>

                                        <!-- media -->
                                        @if(json_decode($message->media) != null)
                                            @if(count(json_decode($message->media)) === 1)
                                                <div class="rounded h-60 min-h-60 max-h-60" uk-lightbox>
                                                    {{-- -------------------------------- --}}
                                                    @if($this->identifyFileType(json_decode($message->media)[0]) == 'video')
                                                        <a data-type="video" class="relative" href="{{ asset('uploads/messages') . '/' . json_decode($message->media)[0] }}">
                                                            <video src="{{ asset('uploads/messages') . '/' . json_decode($message->media)[0] }}" class="sm:rounded-lg w-full h-full object-cover" alt="video"></video>
                                                        
                                                            <div class="absolute top-0 bottom-0 right-0 left-0 flex items-center justify-center">
                                                                <x-icon name="play-circle" solid class="w-10 h-10" />
                                                            </div>
                                                        </a>
                                                    @else
                                                        <a href="{{ asset('uploads/messages') . '/' . json_decode($message->media)[0] }}">
                                                            <img src="{{ asset('uploads/messages') . '/' . json_decode($message->media)[0] }}" class="sm:rounded-lg w-full h-full object-cover" alt="image">
                                                        </a>
                                                    @endif
                                                </div>
                                            @elseif(count(json_decode($message->media)) > 1)
                                                @php
                                                    $count = count(json_decode($message->media)); 
                                                @endphp
                                        
                                                <div class="grid {{ $count == 2 ? 'grid-cols-2' : 'grid-cols-3' }} gap-3">
                                                    @foreach(json_decode($message->media) as $item)
                                                        <div class="col-span-1 rounded h-44 min-h-44 max-h-44" tabindex="-1" style="" uk-lightbox>
                                                            {{-- -------------------------------- --}}
                                                            @if($this->identifyFileType($item) == 'video')
                                                                <a data-type="video" class="relative" href="{{ asset('uploads/messages') . '/' . $item }}">
                                                                    <video src="{{ asset('uploads/messages') . '/' . $item }}" class="w-full h-full object-cover inset-0" alt="video"></video>
                                                                
                                                                    <div class="absolute top-0 bottom-0 right-0 left-0 flex items-center justify-center">
                                                                        <x-icon name="play-circle" solid class="w-10 h-10" />
                                                                    </div>
                                                                </a>
                                                            @else
                                                                <a href="{{ asset('uploads/messages') . '/' . $item }}">
                                                                    <img src="{{ asset('uploads/messages') . '/' . $item }}" class="w-full h-full object-cover inset-0" alt="image">
                                                                </a>
                                                            @endif
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                </div>

                                <div class="!ms-12">
                                    <small>{{ date_format($message->created_at, "m/d/Y g:i A") }}</small>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div> 
        @endif

        @if($conversation->status != App\Enums\Status::Inactive)
            <!-- sending message area -->
            <div class="flex flex-col">
                @if($media)
                    <div class="max-w-[calc(100dvw-37rem)] flex gap-4 overflow-x-auto px-5 pt-5" uk-lightbox>
                        @foreach($media as $key => $item)
                            <div class="flex-shrink-0 w-36 h-36 relative">
                                @if($this->identifyFileType($item->temporaryUrl()) == 'video')
                                    <a data-type="video" class="relative" href="{{ $item->temporaryUrl() }}">
                                        <video src="{{ $item->temporaryUrl() }}" alt="Uploaded Image" class="w-full h-full object-cover rounded-lg shadow border"></video>

                                        <div class="absolute top-0 bottom-0 right-0 left-0 flex items-center justify-center">
                                            <x-icon name="play-circle" solid class="w-10 h-10" />
                                        </div>
                                    </a>
                                @else
                                    <a href="{{ $item->temporaryUrl() }}">
                                        <img src="{{ $item->temporaryUrl() }}" alt="Uploaded Image" class="w-full h-full object-cover rounded-lg shadow border">
                                    </a>
                                @endif

                                <button wire:click="deleteMedia({{ $key }})" class="absolute -top-5 -right-3.5 active:scale-95 transition-all z-[100]">
                                    <x-icon name="x-circle" solid class="w-8 h-8" />
                                </button>
                            </div>  
                        @endforeach
                    </div>
                @endif

                <div class="flex items-center max-md:pe-5 md:gap-4 gap-2 p-3 overflow-hidden">
                    <label class="py-5 flex flex-col justify-center items-center cursor-pointer">
                        <input class="hidden" type="file" multiple wire:model="media">
                        <x-icon name="photo" solid class="w-8 h-8" />
                    </label>
    
                    <x-input label="" wire:model='message' shadowless placeholder="Message"/>
    
                    <x-mini-button rounded flat black icon="paper-airplane" wire:click='sendMessage' />
                </div>
            </div>
        @endif
    @else
        <div class="!w-full !h-screen flex items-center justify-center">
            No Selected Conversation
        </div>
    @endif
</div>