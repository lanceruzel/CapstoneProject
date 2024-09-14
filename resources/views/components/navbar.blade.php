<nav class="bg-indigo-500 shadow border-gray-200 fixed top-0 w-screen" style="z-index: 10">
    <div class="flex items-center justify-stretch px-4 py-3 gap-5">
        <div class="flex items-center justify-center gap-1">
            <x-mini-button rounded icon="bars-3" class="md:hidden" x-on:click="toggleSidebar"/>

            <a href="/" class="flex items-center space-x-3 text-white hover:text-white">
                {{-- <img src="https://flowbite.com/docs/images/logo.svg" class="h-8" alt="Flowbite Logo" /> --}}
                <span class="self-center text-2xl font-semibold whitespace-nowrap dark:text-white">GlobeConnect</span>
            </a>
        </div>
        
        <div class="px-5 w-full flex items-center justify-center">
            <div class="w-full md:max-w-[300px] lg:max-w-[400px]">
                <livewire:Etc.navbar-search />
            </div>
        </div>
    </div>
</nav>