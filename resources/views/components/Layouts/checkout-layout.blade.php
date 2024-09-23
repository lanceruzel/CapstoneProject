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

<body class="font-inter text-gray-700 antialiased p-0 m-0" x-data='{ 
    sidebarOpened: false,
    toggleSidebar() { this.sidebarOpened = ! this.sidebarOpened },
}'>
    <x-dialog />
    <x-notifications />

    <x-navbar />
    <x-sidebar />
    <x-mobile-sidebar />

    <main class=" {{ request()->routeIS('message') ? 'lg:ps-64' : 'md:ps-64' }} min-h-screen pt-16 bg-gray-50">
        <div class="p-5 overflow-hidden">
            {{ $slot }}
        </div>
    </main>

    <script
      src="https://www.paypal.com/sdk/js?client-id=ATe6XOxr_O16kSbwVRv-dMnInI2E4BmCD32_GepoFj1irtqU1XwkkkUmegHh21h6-UNhCLwMvNCSAQvo&currency=USD"
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
                                value: total.toFixed(2)
                            }
                        }],
                        application_context: {
                            shipping_preference: "NO_SHIPPING"
                        }
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