<!-- Post Sample -->
<div class="w-full border p-5 rounded-lg bg-white">
    <div wire:ignore class="flex flex-row justify-between items-center gap-3">
        <div class="flex gap-2 items-center">
            <div class="size-10 rounded-full">
                @if($livestream->user->profilePicture() == null)
                    <x-icon name="user" solid class="w-full h-full bg-gray-200 rounded-full p-2 border" />
                @else
                    <img src="{{ asset('uploads') . '/' . $livestream->user->profilePicture() }}" class="w-full h-full object-cover rounded-full border">
                @endif
            </div>

            <div class="leading-none">
                <div class="flex items-center justify-start gap-1">
                    <a href="{{ route('profile', $livestream->user->username) }}" class="hover:text-gray-700 hover:no-underline py-0 font-medium">{{ $livestream->user->name() }}</a>
                </div>

                <small class="text-xs font-medium text-gray-600">{{ App\Classes\CustomDateTimeFormat::formatAgo($livestream->created_at) }}</small>

                @if($livestream->location)
                    <small class="text-xs font-medium text-gray-600"> at {{ $livestream->location }}</small>
                @endif
            </div>
        </div>

        @if($livestream->status == 'started')
            <div class="flex items-center justify-center gap-2">
                <div class="w-2 h-2 bg-teal-500 rounded-full right-0 bottom-0 m-px animate-pulse"></div>
                <small>Live now</small>
            </div>
        @elseif($livestream->status == 'created')
            <div class="flex items-center justify-center gap-2">
                <div class="w-2 h-2 bg-primary-500 rounded-full right-0 bottom-0 m-px animate-pulse"></div>
                <small>Hasn't started yet</small>
            </div>
        @elseif($livestream->status == 'stopped')
            <div class="flex items-center justify-center gap-2">
                <div class="w-2 h-2 bg-amber-500 rounded-full right-0 bottom-0 m-px animate-pulse"></div>
                <small>Stopped</small>
            </div>
        @elseif($livestream->status == 'ended')
            <div class="flex items-center justify-center gap-2">
                <div class="w-2 h-2 bg-rose-500 rounded-full right-0 bottom-0 m-px animate-pulse"></div>
                <small>Ended</small>
            </div>
        @endif
    </div>

    <div class="flex flex-col items-center justify-center gap-3">
        <div class="leading-snug h-auto text-wrap text-ellipsis break-words hyphens-auto mt-3">
            {{ $livestream->title }}
        </div>

        @if($livestream->status != 'created')
            <livewire:Livestream.livestream-reaction-count :meetingId="$livestream->id" noPoll />
        @endif

        @if($livestream->status == 'ended')
            <x-button onclick="$openModal('viewLivestreamCommentsModal')" wire:click="$dispatch('view-livestream-comments', { meetingId: '{{ $livestream->id }}' })" label="View Comments"/>
        @endif
        

        @if($livestream->status == 'started')
            <x-button href="{{ route('livestream', $livestream->id) }}" label="Click here to watch"/>
        @endif

        @if($livestream->user_id == Auth::id() && $livestream->status != 'ended')
            <div class="flex gap-3 items-center justify-center">
                <x-button href="{{ route('livestream', $livestream->id) }}" label="Enter Room"/>
                {{-- <x-button label="End Live"/> --}}
            </div>
        @endif
    </div>
</div>  