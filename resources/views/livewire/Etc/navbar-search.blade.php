<x-dropdown class="w-full" icon="bars-3" width="7xl" position="bottom" fullwidth>
    <x-slot name="trigger" class="w-full">
        <x-input icon="magnifying-glass" wire:model.live.debounce.300ms="search" placeholder="Search" shadowless />
    </x-slot>

    <x-dropdown.header label="User">
        @foreach($users as $user)
            <x-dropdown.item separator onclick="location.href='{{ route('profile', $user->user->username) }}'">
                <div class="w-full flex items-center justify-between cursor-pointer gap-3">
                    <div class="flex items-center justify-center gap-3">
                        <div class="flex items-center justify-center">
                            @if($user->user->profilePicture() == null)
                                <x-avatar sm icon-size="xs" icon="user" primary />
                            @else
                                <x-avatar sm src="{{ asset('uploads') . '/' . $user->user->profilePicture() }}" />
                            @endif
                        </div>
    
                        <div class="leading-snug hover:no-underline">
                            <p class="font-semibold text-gray-700">{{ $user->user->name() }}</p>
                            <small class="text-gray-700">
                                <span class="font-semibold me-1">{{ count($user->user->posts) }}</span>posts
                            </small>
                        </div>
                    </div>
                 
                    <span class="inline text-slate-500 hover:text-slate-600 text-sm font-semibold" onclick="location.href='{{ route('message', $user->user->username) }}'">Message</span>
                </div>
            </x-dropdown.item>
        @endforeach
    </x-dropdown.header>

    <x-dropdown.header label="Store">
        @foreach($stores as $store)
            <x-dropdown.item separator onclick="location.href='{{ route('profile', $user->user->username) }}'">
                <div class="w-full flex items-center justify-between cursor-pointer gap-3">
                    <div class="flex items-center justify-center gap-3">
                        <div class="flex items-center justify-center">
                            @if($store->user->profilePicture() == null)
                                <x-avatar sm icon-size="xs" icon="user" primary />
                            @else
                                <x-avatar sm src="{{ asset('uploads') . '/' . $store->user->profilePicture() }}" />
                            @endif
                        </div>
    
                        <div class="leading-snug hover:no-underline">
                            <p class="font-semibold text-gray-700">{{ $store->user->name() }}</p>
                            <small class="text-gray-700">
                                <span class="font-semibold me-1">{{ count($store->user->products) }}</span>products
                            </small>
                        </div>
                    </div>
                 
                    <span class="inline text-slate-500 hover:text-slate-600 text-sm font-semibold" onclick="location.href='{{ route('message', $user->user->username) }}'">Message</span>
                </div>
            </x-dropdown.item>
        @endforeach
    </x-dropdown.header>
</x-dropdown>
