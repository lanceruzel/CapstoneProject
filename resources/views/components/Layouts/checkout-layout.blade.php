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
    <meta name="user-id" content="{{ Auth::user()->id }}">
    <title>GlobeConnect</title>

    <script src="https://cdn.jsdelivr.net/npm/uikit@3.21.5/dist/js/uikit.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/uikit@3.21.5/dist/css/uikit.min.css" />

    @wireUiScripts
    @vite(['resources/css/app.css','resources/js/app.js'])
    {{-- <script src="//unpkg.com/alpinejs" defer></script> --}}

    <style>
        .uk-active>a {
            border-bottom-color: rgb(107 114 128) !important;
            text-decoration: none !important;
        }

        a {
            border-bottom-color: rgb(107 114 128) !important;
            text-decoration: none !important;
        }

        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="font-inter text-gray-700 antialiased p-0 m-0">
    <x-dialog />
    <x-notifications />

    <nav class="bg-white shadow border-gray-200 fixed top-0 w-screen" style="z-index: 10">
        <div class="flex flex-wrap justify-between items-center space-x-3 p-4">
            <a href="/" class="flex items-center space-x-3">
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

    <!-- Sidebar -->
    <aside class="{{ request()->routeIS('message') ? 'max-lg:hidden' : 'max-md:hidden' }} transition-all fixed top-auto h-full w-64 left-0 border-e pt-16 bg-white z-[5]">
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
                    <x-button class='!justify-start font-medium' xl icon='bell' href='#' flat full secondary label="Notification" uk-toggle="target: #notification-slide" />
                </li>

                <li>
                    <x-button class='!justify-start font-medium' xl icon='magnifying-glass' href='#' flat full secondary label="Search" uk-toggle="target: #search-slide" />
                </li>

                <li>
                    @if(request()->routeIS('market'))
                        <x-button class='!justify-start font-medium' xl icon='shopping-bag' href="{{ route('market') }}" solid flat full secondary label="Market" />
                    @else
                        <x-button class='!justify-start font-medium' xl icon='shopping-bag' href="{{ route('market') }}" flat full secondary label="Market" /> 
                    @endif
                </li>

                @if(auth()->user()->role != App\Enums\UserType::Store)
                    <li>
                        @if(request()->routeIS('cart'))
                            <x-button class='!justify-start font-medium' xl icon='shopping-cart' href="{{ route('cart') }}" solid flat full secondary label="My Cart" />
                        @else
                            <x-button class='!justify-start font-medium' xl icon='shopping-cart' href="{{ route('cart') }}" flat full secondary label="My Cart" /> 
                        @endif
                    </li>
                @endif

                <li>
                    @if(request()->routeIS('message'))
                        <x-button class='!justify-start font-medium' xl icon='chat-bubble-bottom-center-text' href="{{ route('message') }}" solid flat full secondary label="Messages" />
                    @else
                        <x-button class='!justify-start font-medium' xl icon='chat-bubble-bottom-center-text' href="{{ route('message') }}" flat full secondary label="Messages" /> 
                    @endif
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
                    <x-button class='!justify-start !w-full' icon='cog-6-tooth' flat full secondary label="{{ auth()->user()->name() }}" />
                </x-slot>
                
                @if(auth()->user()->role == App\Enums\UserType::Store || auth()->user()->role == App\Enums\UserType::Travelpreneur)
                    @if($storeRegistration->isRegistered())
                        <x-dropdown.item href="{{ route('store.dashboard') }}" icon='building-storefront' label="Store Management" />
                    @else
                        <x-dropdown.item icon='building-storefront' label="Register Store" onclick="$openModal('storeRegistrationFormModal')" />
                    @endif
                @else
                    <x-dropdown.item icon='user-group' label="Affiliates" onclick="$openModal('affiliateDashboardModal')" />
                @endif

                <x-dropdown.item separator icon='arrow-left-end-on-rectangle' href="{{ route('signout') }}" label="Sign out" />
            </x-dropdown>
        </div>
    </aside>

    <main class=" {{ request()->routeIS('message') ? 'lg:ps-64' : 'md:ps-64' }} min-h-screen pt-16 bg-gray-50">
        <div class="p-5 overflow-hidden">
            {{ $slot }}
        </div>
    </main>

    <script
      src="https://www.paypal.com/sdk/js?client-id=AZVVsNkGV7NS6SL25L-l2mG9qelgsvcuURxGMNlcEIk4xqlmUuGSek5kJYe2np3Z_ew2kLdEmj4Hy6tx&currency=USD"
      data-sdk-integration-source="developer-studio"
    ></script>

    <script>
        const userId = document.querySelector('meta[name="user-id"]').getAttribute('content');

        document.addEventListener('livewire:init', () => {
            total = 0;

            Livewire.on('getTotal', (event) => {
                total = event[0].total;
            });

            Livewire.on('close-modal', (event) => {
                $closeModal(event[0].modal);
            });

            Livewire.on('messagesUpdated', function () {
                scrollToBottom();
            });

            Livewire.on('update-post', function () {
                $openModal('postFormModal');
            });

            window.Echo.channel(`new-notification.${userId}`)
                .listen('NotificationCreated', (e) => {
                    $wireui.notify({
                        icon: 'info',
                        title: 'Info!',
                        description: 'You have received new notification!'
                    })
                }
            );

            paypal.Buttons({
                createOrder: function(data, actions){
                    return actions.order.create({
                        purchase_units: [{
                            amount: {
                                value: total
                            }
                        }]
                    })
                },
                onApprove: function(data, actions){
                    return actions.order.capture().then(function(details){
                        //reference number
                        // console.log(details.purchase_units[0].payments.captures[0].id);
                        Livewire.dispatch('payment-completed', { status: details.status, referenceID: details.purchase_units[0].payments.captures[0].id });
                    })
                },
            }).render('#paypal-button-container').then(() => {
                // Disable the button after rendering
                document.querySelector('#paypal-button-container').style.pointerEvents = 'none';
                document.querySelector('#paypal-button-container').style.opacity = '0.5'; 
            });

            Livewire.on('enable-paypal-button', function() {
                document.querySelector('#paypal-button-container').style.pointerEvents = 'auto';
                document.querySelector('#paypal-button-container').style.opacity = '1'; 
            });
        });
    </script>
</body>

</html>