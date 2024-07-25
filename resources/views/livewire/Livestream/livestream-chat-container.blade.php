<div class="h-fit bg-white rounded-lg shadow">
    <div class="p-3 shadow">
        <div class="flex items-center justify-between">
            <p class="text-xl font-semibold">Chats</p>

            <p>Watching now: <span id="watchingCount">0</span></p>
        </div>
    </div>

    <div class="p-5 text-sm font-medium space-y-5 overflow-y-auto h-[400px] md:h-[calc(100vh-18rem)]">
        @foreach ($comments as $comment)
            @if($comment->user_id == Auth::id())
                <!-- sent -->
                <div class="flex flex-col gap-1 items-end">
                    {{-- <img src="https://i.pravatar.cc" alt="" class="w-5 h-5 rounded-full shadow"> --}}
                    <div class="px-4 py-2 rounded-[20px] max-w-sm bg-gradient-to-tr from-sky-500 to-blue-500 text-white shadow break-words text-wrap hyphens-auto space-y-3 text-sm">
                        <span>{{ $comment->content }}</span>
                    </div>
                        <small class="pe-2">{{ $comment->getDateTimeDiff() }}</small>
                </div> 
            @else
                <!-- received -->
                <div>
                    <div class="ms-12">
                        <small class="font-semibold">{{ $comment->user->name() }}</small>
                    </div>

                    <div class="flex gap-3">
                        <img src="{{ asset('uploads') . '/' . $comment->user->profilePicture() }}" alt="" class="w-9 h-9 rounded-full shadow">
                        <div class="px-4 py-2 rounded-[20px] max-w-sm bg-gray-100 break-words !text-wrap hyphens-auto space-y-3 text-sm">
                            <span>{{ $comment->content }}</span>
                        </div>
                    </div>

                    <div class="!ms-14">
                        <small>{{ $comment->getDateTimeDiff() }}</small>
                    </div>
                </div>
            @endif
        @endforeach
    </div>

    <div class="flex items-center md:gap-4 gap-2 p-3 overflow-hidden">
        <x-input label="" wire:model='content' placeholder="Message" />
        
        <x-mini-button rounded flat black icon="paper-airplane" wire:click='sendMessage' />
    </div>
</div>