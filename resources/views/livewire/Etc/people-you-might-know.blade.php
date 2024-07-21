<div class="bg-white rounded-xl shadow-sm p-5 px-6">

    <!-- Header -->
    <div class="flex justify-between items-center">
        <h3 class="font-bold"> People You might know </h3>
        
        <x-mini-button rounded icon="arrow-path" wire:click='$refresh' spinner outline flat primary interaction:solid />
    </div>

    <!-- Content -->
    <div class="space-y-4 capitalize text-xs font-normal mt-5 mb-2 text-gray-500">
        @if(count($users) > 0)
            @foreach ($users as $user)
                <div class="flex items-center gap-3">
                    <a href="{{ route('profile', $user->username) }}">
                        @if($user->profilePicture() == null)
                            <img src="https://static.everypixel.com/ep-pixabay/0329/8099/0858/84037/3298099085884037069-head.png" alt="" class="bg-gray-100 object-cover rounded-full h-10 w-10">
                        @else
                            <img src="{{ asset('uploads') . '/' . $user->profilePicture() }}" class="object-cover rounded-full w-10 h-10">
                        @endif
                    </a>

                    <div class="flex-1">
                        <a href="{{ route('profile', $user->username) }}">
                            <h4 class="font-semibold text-sm text-gray-700 dark:text-white">{{ $user->userInformation->fullname() }}</h4>
                        </a>

                        <div class="mt-0.5">Suggested For You</div>
                    </div>
                    
                    <a href="{{ route('message', $user->username) }}" class="hover:no-underline hover:text-gray-600 text-sm rounded-full py-1.5 px-4 font-semibold">
                        Message
                    </a>
                </div>
            @endforeach
        @else
            <div class="flex items-center justify-center">
                There are currently no users
            </div>
        @endif
    </div>
</div>