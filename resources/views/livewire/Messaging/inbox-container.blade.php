<div>
    <!-- heading title -->
    <div class="p-4 border-b dark:border-slate-700">
                        
        <div class="flex mt-2 items-center justify-between">

            <h2 class="text-2xl font-bold text-black ml-1 dark:text-white"> Chats </h2>
                  
            <!-- right action buttons -->
            {{-- <div class="flex items-center gap-2.5">

                
                <button class="group" aria-haspopup="true" aria-expanded="false">
                    <ion-icon name="settings-outline" class="text-2xl flex group-aria-expanded:rotate-180 md hydrated" role="img" aria-label="settings outline"></ion-icon>
                </button>
                <div class="md:w-[270px] w-full uk-dropdown" uk-dropdown="pos: bottom-left; offset:10; animation: uk-animation-slide-bottom-small"> 
                    <nav>
                        <a href="#"> <ion-icon class="text-2xl shrink-0 -ml-1 md hydrated" name="checkmark-outline" role="img" aria-label="checkmark outline"></ion-icon> Mark all as read </a>  
                        <a href="#"> <ion-icon class="text-2xl shrink-0 -ml-1 md hydrated" name="notifications-outline" role="img" aria-label="notifications outline"></ion-icon> notifications setting </a>  
                        <a href="#"> <ion-icon class="text-xl shrink-0 -ml-1 md hydrated" name="volume-mute-outline" role="img" aria-label="volume mute outline"></ion-icon> Mute notifications </a>     
                    </nav>
                </div>
                
                <button class="">
                    <ion-icon name="checkmark-circle-outline" class="text-2xl flex md hydrated" role="img" aria-label="checkmark circle outline"></ion-icon>
                </button>

                <!-- mobile toggle menu -->
                <button type="button" class="md:hidden" uk-toggle="target: #side-chat ; cls: max-md:-translate-x-full" aria-expanded="false">
                    <ion-icon name="chevron-down-outline" role="img" class="md hydrated" aria-label="chevron down outline"></ion-icon>
                </button>

            </div> --}}

        </div>

        <!-- search -->
        <div class="relative mt-4">
            <x-input icon="magnifying-glass" wire:model.live.debounce.200ms="search" placeholder="Search" shadowless />
        </div> 

    </div> 

    <!-- users list -->
    <div class="space-y-2 p-2 overflow-y-auto h-[calc(100vh-127px)]">
        @if($conversations->count() > 0)
            @foreach ($conversations as $convo)
                <div class="relative flex items-center gap-4 p-2 duration-200 rounded-xl hover:bg-gray-100 cursor-pointer" wire:click="$dispatch('view-conversation', { id: {{ $convo->id }} })">
                    <div class="relative w-14 h-14 shrink-0"> 
                        <img src="https://i.pravatar.cc" alt="" class="object-cover w-full h-full rounded-full">
                    </div>
                    
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-1.5">
                            @if($convo->receiver_id != Auth::id())
                                <div class="mr-auto text-sm text-black dark:text-white font-medium">{{ $convo->user1->role == App\Enums\UserType::Store ? $convo->user1->storeInformation->name : $convo->user1->userInformation->fullname() }}</div>
                            @else
                                <div class="mr-auto text-sm text-black dark:text-white font-medium">{{ $convo->user2->role == App\Enums\UserType::Store ? $convo->user2->storeInformation->name : $convo->user1->userInformation->fullname() }}</div>
                            @endif
                            
                            <div class="text-xs font-light text-gray-500 dark:text-white/70">{{ $convo->last_message_id ? $this->getDateTimeDiff($convo->lastMessage->updated_at) : null }}</div> 
                        </div>

                        <!-- Last Chat Preview -->
                        <div class="font-medium overflow-hidden text-ellipsis text-sm whitespace-nowrap">{{ $convo->lastMessage->user_id == Auth::id() ? 'You: ' : null }} {{ $convo->last_message_id ? $convo->lastMessage->content : 'No message' }}</div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="w-full h-full flex items-center justify-center">
                <h1>There are no conversation available</h1>
            </div>
        @endif
    </div>
</div>