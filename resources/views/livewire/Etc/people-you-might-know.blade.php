<div class="bg-white rounded-xl shadow-sm p-5 px-6 border">

    <!-- Header -->
    <div class="flex justify-between items-center">
        <h3 class="font-bold"> People You might know </h3>
        
        <x-mini-button flat rounded icon="arrow-path" wire:click='$refresh' spinner outline flat primary interaction:solid />
    </div>

    <!-- Content -->
    <div class="space-y-4 text-xs font-normal mt-5 mb-2 text-gray-500">
        @if(count($users) > 0)
            @foreach ($users as $user)
                <div class="flex items-center gap-3 capitalize">
                    <a href="{{ route('profile', $user->username) }}" class="relative">
                        @if($user->profilePicture() == null)
                            <x-icon name="user" solid class="w-10 h-10 bg-gray-200 rounded-full p-2 border" />
                        @else
                            <img src="{{ asset('uploads') . '/' . $user->profilePicture() }}" class="object-cover rounded-full w-10 h-10 border">
                        @endif

                        @if($user->isOnline())  
                            <div class="absolute size-3 bg-green-500 rounded-full top-0 -right-0.5"></div>
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
            <div class="flex items-center justify-center text-gray-500 text-xs font-medium">
                There are currently no users
            </div>
        @endif
    </div>
</div>