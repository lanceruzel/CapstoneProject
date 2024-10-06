<div class="h-fit bg-white rounded-lg shadow">
    <div class="p-3">
        <div class="flex items-center justify-between border-b pb-2">
            <p class="text-xl font-semibold">Chats</p>

            <p>Watching now: <span id="watchingCount">0</span></p>
        </div>

        <livewire:Livestream.livestream-reaction-count :meetingId="$meetingId" />

    </div>

    <div class="p-5 text-sm font-medium space-y-5 overflow-y-auto h-[400px] md:h-[calc(100vh-18rem)]">
        @foreach ($comments as $comment)
            @if($comment->user_id == Auth::id())
                <!-- sent -->
                <div class="flex flex-col gap-1 items-end">
                    <div class="px-4 py-2 rounded-[20px] max-w-sm bg-gradient-to-tr from-sky-500 to-blue-500 text-white shadow break-words text-wrap hyphens-auto space-y-3 text-sm">
                        {!! html_entity_decode($comment['content']) !!}
                    </div>
                    
                    <small class="pe-2">{{ App\Classes\CustomDateTimeFormat::formatAgo($comment->created_at) }}</small>
                </div> 
            @else
                <!-- received -->
                <div>
                    <div class="ms-12">
                        <small class="font-semibold">{{ $comment->user->name() }}</small>
                    </div>

                    <div class="flex gap-3">
                        @if($comment->user->profilePicture() == null)
                            <x-icon name="user" solid class="w-9 h-9 rounded-full border bg-gray-200" />
                        @else
                            <img src="{{ asset('uploads') . '/' . $comment->user->profilePicture() }}" class="w-full h-full object-cover rounded-full border">
                        @endif

                        <div class="px-4 py-2 rounded-[20px] max-w-sm bg-gray-100 break-words !text-wrap hyphens-auto space-y-3 text-sm">
                            {!! html_entity_decode($comment['content']) !!}
                        </div>
                    </div>

                    <div class="!ms-14">
                        <small>{{ App\Classes\CustomDateTimeFormat::formatAgo($comment->created_at) }}</small>
                    </div>
                </div>
            @endif
        @endforeach
    </div>

    <div class="flex items-center gap-2 p-3">
        <x-dropdown position="top">
            <x-slot name="trigger">
                <x-mini-button rounded flat black icon="face-smile" />
            </x-slot>

            <x-dropdown.item class="!px-2 hover:!bg-transparent">
                <livewire:Livestream.livestream-reaction-container :meetingId="$meetingId" />
            </x-dropdown.item>
        </x-dropdown>

        <x-input label="" wire:model='content' placeholder="Message" shadowless />
        
        <x-mini-button rounded flat black icon="paper-airplane" wire:click='sendMessage' />
    </div>
</div>