<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    <script src="https://cdn.jsdelivr.net/npm/uikit@3.21.5/dist/js/uikit.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/uikit@3.21.5/dist/css/uikit.min.css" />
    
    @wireUiScripts
    @vite(['resources/css/app.css','resources/js/app.js'])
    {{-- <script src="//unpkg.com/alpinejs" defer></script> --}}

    <style>
        .uk-active > a{
            border-bottom-color: rgb(107 114 128) !important;
            text-decoration: none !important;
        }

        a{
            border-bottom-color: rgb(107 114 128) !important;
            text-decoration: none !important;
        }

        [x-cloak]{
            display: none !important;
        }
    </style>
</head>

<body class="font-inter text-gray-700 antialiased p-0 m-0">
    <x-dialog />
    <x-notifications />
    
    <nav class="bg-white shadow border-gray-200 fixed top-0 w-screen" style="z-index: 1">
        <div class="flex flex-wrap items-center justify-between mx-auto p-4">
            <a href="/" class="flex items-center space-x-3">
                {{-- <img src="https://flowbite.com/docs/images/logo.svg" class="h-8" alt="Flowbite Logo" /> --}}
                <span class="self-center text-2xl font-semibold whitespace-nowrap dark:text-white">GlobeConnect</span>
            </a>

            <div>
                <ul class="w-full max-md:flex hidden items-center justify-center flex-row">
                    <li>
                        <x-mini-button rounded icon="user-circle" href='#' flat primary interaction:solid />
                    </li>
    
                    <li>
                        <x-mini-button rounded icon="Home" href='#' flat primary interaction:solid />
                    </li>
    
                    <li>
                        <x-mini-button rounded icon="bell" href='#' flat primary interaction:solid />
                    </li>
    
                    <li>
                        <x-mini-button rounded icon="magnifying-glass" href='#' flat primary interaction:solid />
                    </li>
    
                    <li>
                        <x-mini-button rounded icon="chat-bubble-bottom-center-text" href='#' flat primary interaction:solid />
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Sidebar -->
    <aside class="max-md:hidden fixed top-auto h-full w-64 left-0 border-e pt-16 bg-white">
        <div class="py-3 px-2.5 flex items-center justify-between flex-col h-full">
            <ul class="space-y-2 w-full">
                <li>
                    @if(request()->routeIS('home'))
                        <x-button class='!justify-start font-medium' xl icon='home' href="{{ route('home') }}" solid flat full secondary label="Home" />
                    @else
                        <x-button class='!justify-start font-medium' xl icon='home' href="{{ route('home') }}" flat full secondary label="Home" /> 
                    @endif
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

                <li>
                    @if(request()->routeIS('profile'))
                        <x-button class='!justify-start font-medium' xl icon='user-circle' href="{{ route('profile') }}" solid flat full secondary label="Profile" />   
                    @else
                        <x-button class='!justify-start font-medium' xl icon='user-circle' href="{{ route('profile') }}" flat full secondary label="Profile" />
                    @endif 
                </li>
            </ul>

            <x-dropdown position="top-start">
                <x-slot name="trigger">
                    <x-button class='!justify-start' icon='cog-6-tooth' flat full secondary label="Lance Ruzel C. Ambrocio" />
                </x-slot>
                
                <x-dropdown.item icon='building-storefront' href="/}" label="Store Management" />
                <x-dropdown.item icon='user-group' href="/" label="Affiliates" />
                <x-dropdown.item separator icon='arrow-left-end-on-rectangle' href="{{ route('signout') }}" label="Sign out" />
            </x-dropdown>
        </div>
    </aside>

    <main class="md:ps-64 pt-16 bg-gray-50">
        <div class="p-5 overflow-hidden">
            {{ $slot }}
        </div>
    </main>

    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('close-modal', (event) => {
                $closeModal(event[0].modal);
            });

            Livewire.on('messagesUpdated', function () {
                scrollToBottom();
            });

            Livewire.on('update-post', function () {
                $openModal('postFormModal');
            });
        });

        function copyToClipboard(textToCopy) {
            var $temp = $("<input>");
            $("body").append($temp);
            $temp.val(textToCopy).select();
            document.execCommand("copy");
            toast('success', 'Copied to clipboard.');
            $temp.remove();
        }

        function scrollToBottom() {
            var chatContainer = document.getElementById("chat-container");
            chatContainer.scrollTop = chatContainer.scrollHeight;
        }

        // Call scrollToBottom when the page loads
        window.onload = function() {
            scrollToBottom();
        }
    </script>
</body>
</html>