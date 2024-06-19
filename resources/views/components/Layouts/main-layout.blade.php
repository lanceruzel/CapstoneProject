<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    @wireUiScripts
    @vite(['resources/css/app.css','resources/js/app.js'])
    {{-- <script src="//unpkg.com/alpinejs" defer></script> --}}
</head>

<body class="font-inter text-gray-700 antialiased p-0 m-0">
    <x-dialog />
    <x-notifications />
    
    <nav class="bg-white shadow border-gray-200 fixed top-0 w-screen z-60">
        <div class="flex flex-wrap items-center justify-between mx-auto p-4">
            <a href="/" class="flex items-center space-x-3">
                {{-- <img src="https://flowbite.com/docs/images/logo.svg" class="h-8" alt="Flowbite Logo" /> --}}
                <span class="self-center text-2xl font-semibold whitespace-nowrap dark:text-white">GlobeConnect</span>
            </a>
        </div>
    </nav>

    <!-- Sidebar -->
    <aside class="fixed top-auto h-full w-64 left-0 border-e pt-16 bg-white">
        <div class="py-3 px-2.5 flex items-center justify-between flex-col h-full">
            <ul class="space-y-2 w-full">
                <li>
                    <x-button class='!justify-start font-medium' xl icon='user-circle' href='#' flat full secondary label="Profile" />
                </li>

                <li>
                    <x-button class='!justify-start font-medium' xl icon='home' href='#' flat full secondary label="Home" />
                </li>

                <li>
                    <x-button class='!justify-start font-medium' xl icon='bell' href='#' flat full secondary label="Notification" />
                </li>

                <li>
                    <x-button class='!justify-start font-medium' xl icon='magnifying-glass' href='#' flat full secondary label="Search" />
                </li>

                <li>
                    <x-button class='!justify-start font-medium' xl icon='chat-bubble-bottom-center-text' href='#' flat full secondary label="Messages" />
                </li>
            </ul>

            <x-dropdown position="top-start">
                <x-slot name="trigger">
                    <x-button class='!justify-start' icon='cog-6-tooth' flat full secondary label="Lance Ruzel C. Ambrocio" />
                </x-slot>
                
                <x-dropdown.item icon='arrow-left-end-on-rectangle' label="Logout" />
            </x-dropdown>
        </div>
    </aside>

    <main class="ps-64 pt-16 bg-gray-50">
        <div class="p-5">
            {{ $slot }}
        </div>
    </main>
</body>

</html>