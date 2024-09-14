<nav class="bg-indigo-500 shadow border-gray-200 fixed top-0 w-screen" style="z-index: 10">
    <div class="flex flex-wrap justify-between items-center space-x-3 p-4">
        <a href="/" class="flex items-center space-x-3 text-white hover:text-white">
            {{-- <img src="https://flowbite.com/docs/images/logo.svg" class="h-8" alt="Flowbite Logo" /> --}}
            <span class="self-center text-2xl font-semibold whitespace-nowrap dark:text-white">GlobeConnect</span>
        </a>

        <div class="md:hidden space-x-2">
            <x-mini-button rounded icon="bell" flat gray uk-toggle="target: #notification-slide" />
            <x-mini-button rounded icon="chat-bubble-bottom-center-text" href="{{ route('message') }}" flat gray />

            @if(auth()->user()->role != App\Enums\UserType::Store)
                <x-mini-button rounded icon="shopping-cart" href="{{ route('cart') }}" flat gray />
            @endif
        </div>
    </div>
</nav>