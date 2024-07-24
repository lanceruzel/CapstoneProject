<!-- Post Sample -->
<div class="w-full border p-5 rounded-lg bg-white">
    <div wire:ignore class="flex flex-row justify-between items-center gap-3">
        <div class="flex gap-2 items-center">
            <div class="size-10 rounded-full">
                @if($livestream->user->profilePicture() == null)
                    <img src="https://static.everypixel.com/ep-pixabay/0329/8099/0858/84037/3298099085884037069-head.png" alt="" class="bg-gray-100 w-full h-full object-cover rounded-full">
                @else
                    <img src="{{ asset('uploads') . '/' . $livestream->user->profilePicture() }}" class="w-full h-full object-cover rounded-full">
                @endif
            </div>

            <div class="leading-none">
                <div class="flex items-center justify-start gap-1">
                    <a href="{{ route('profile', $livestream->user->username) }}" class="hover:text-gray-700 hover:no-underline py-0 font-medium">{{ $livestream->user->name() }}</a>
                </div>

                <small class="text-xs font-medium text-gray-600">{{ $this->getDateTimeDiff() }}</small>
            </div>
        </div>

        <div class="flex items-center justify-center gap-2">
            <div class="w-2 h-2 bg-teal-500 rounded-full right-0 bottom-0 m-px animate-pulse"></div>
            <small>Live now</small>
        </div>
    </div>

    <div class="flex flex-col items-center justify-center gap-3">
        <div class="leading-snug h-auto text-wrap text-ellipsis break-words hyphens-auto mt-3">
            {{ $livestream->title }}
        </div>

        <x-button href="{{ route('livestream', $livestream->id) }}" label="Click here to watch"/>
    </div>
</div>  