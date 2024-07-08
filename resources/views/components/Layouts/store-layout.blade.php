@php
    use App\Classes\StoreRegistration;

    $storeRegistration = new StoreRegistration();
@endphp

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

<body class="font-inter text-gray-700 antialiased p-0 m-0" x-data='{ 
    sidebarOpened: true,
    toggleSidebar() { this.sidebarOpened = ! this.sidebarOpened },
}'>
    <x-dialog />
    <x-notifications />
    
    <nav class="bg-white shadow border-gray-200 fixed top-0 w-screen" style="z-index: 1">
        <div class="flex flex-wrap items-center mx-auto p-4">
            <x-mini-button x-on:click="toggleSidebar" rounded icon="bars-3-bottom-left" white interaction:solid />

            <a href="/" class="flex items-center space-x-3">
                {{-- <img src="https://flowbite.com/docs/images/logo.svg" class="h-8" alt="Flowbite Logo" /> --}}
                <span class="self-center text-2xl font-semibold whitespace-nowrap dark:text-white">Store Management</span>
            </a>
        </div>
    </nav>

    <!-- Sidebar -->
    <aside x-bind:class="sidebarOpened ? 'translate-x-0' : '-translate-x-full'" x-transition class="transition-all fixed top-auto h-full w-64 left-0 border-e pt-16 bg-white">
        <div class="py-3 px-2.5 flex items-center justify-between flex-col h-full">
            <ul class="space-y-2 w-full">
                <li>
                    @if(request()->routeIS('store.product-management'))
                        <x-button class='!justify-start font-medium' icon='folder' href="{{ route('store.product-management') }}" solid flat full secondary label="Product Management" />
                    @else
                        <x-button class='!justify-start font-medium' icon='folder' href="{{ route('store.product-management') }}" flat full secondary label="Product Management" /> 
                    @endif
                </li>

                <li>
                    @if(request()->routeIS('store.order-management'))
                        <x-button class='!justify-start font-medium' icon='folder' href="{{ route('store.order-management') }}" solid flat full secondary label="Order Management" />
                    @else
                        <x-button class='!justify-start font-medium' icon='folder' href="{{ route('store.order-management') }}" flat full secondary label="Order Management" /> 
                    @endif
                </li>
            </ul>

            <x-button href='/' icon='arrow-left-end-on-rectangle' white label="Go Home" />
        </div>
    </aside>

    <main x-bind:class="sidebarOpened ? 'md:!ps-64' : ''" x-transition class="pt-16 bg-gray-50 min-h-screen transition-all">
        <div class="p-5 !pt-10 overflow-hidden">
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