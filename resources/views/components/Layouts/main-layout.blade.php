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
    
    <link href="https://vjs.zencdn.net/7.20.3/video-js.css" rel="stylesheet" />
    <script defer src="https://vjs.zencdn.net/7.20.3/video.min.js"></script>

    @wireUiScripts
    @vite(['resources/css/app.css','resources/js/app.js'])
    {{-- <script src="//unpkg.com/alpinejs" defer></script> --}}

    <style>
        .uk-active > a{
            border-bottom-color: rgb(107 114 128) !important;
            text-decoration: none !important;
        }

        .uk-tab > li > a {
            border-bottom: none !important;
        }

        .uk-tab > li.uk-active > a {
            border-bottom: 2px solid #000 !important; /* Adjust the border size and color as needed */
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
    sidebarOpened: false,
    toggleSidebar() { this.sidebarOpened = ! this.sidebarOpened },
}'>
    <x-dialog />
    <x-notifications />
    
    <x-navbar />
    <x-sidebar />
    <x-mobile-sidebar />

    <main x-bind:class="sidebarOpened ? 'md:!ps-64' : ''" x-transition class="{{ request()->routeIS('message') ? 'lg:ps-64' : 'md:ps-64' }} min-h-screen pt-16 bg-gray-50 transition-all pb-16 md:pb-0 overflow-hidden">
        <div class="p-5">
            {{ $slot }}
        </div>
    </main>

    <livewire:Etc.select-currency-modal />
    <livewire:Product.product-view-modal />
    <livewire:Product.product-view-variation-selection-modal />

    @if(auth()->user()->role == App\Enums\UserType::Store || auth()->user()->role == App\Enums\UserType::Travelpreneur)
        <livewire:StoreRegistration.store-register-form-modal />
    @else
        <livewire:Affiliate.affiliate-dashboard-modal />
        <livewire:Affiliate.affiliate-payout-form-modal />
        <livewire:Affiliate.payout-history-modal />
        <livewire:Affiliate.affiliate-invitation-modal />
        <livewire:Affiliate.view-terms-and-condition-modal />
    @endif

    @if(request()->routeIS('livestream') || request()->routeIS('home'))
        <script src="https://sdk.videosdk.live/js-sdk/0.0.67/videosdk.js"></script>

        <!-- hls lib script  -->
        <script src="https://cdn.jsdelivr.net/npm/hls.js"></script>

        @if(request()->routeIS('home'))
            <script>
                // if (navigator.geolocation) {
                //     navigator.geolocation.getCurrentPosition(
                //         function (position) {
                //             // Send coordinates to Livewire if successful
                //             Livewire.dispatch('getGeolocation', { 
                //                 latitude: position.coords.latitude, 
                //                 longitude: position.coords.longitude 
                //             });
                //         },
                //         function (error) {
                //             // Handle errors
                //             switch (error.code) {
                //                 case error.PERMISSION_DENIED:
                //                     //alert('GPS permission is denied. Please enable location services and grant permission.');
                //                     break;
                //                 case error.POSITION_UNAVAILABLE:
                //                     alert('Location information is unavailable. Please enable your GPS position feature.');
                //                     break;
                //                 case error.TIMEOUT:
                //                     alert('Request for location timed out. Please try again.');
                //                     break;
                //                 default:
                //                     alert('An unknown error occurred. Please enable your GPS and try again.');
                //             }
                //         },
                //         {
                //             maximumAge: 10000,
                //             timeout: 5000,
                //             enableHighAccuracy: true
                //         }
                //     );
                // } else {
                //     console.log('Geolocation is not supported by this browser.');
                // }

                const createButton = document.getElementById("createMeetingBtn");

                createButton.addEventListener("click", async () => {
                    if (navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition(
                            async function (position) {
                                // Send coordinates to Livewire if successful
                                Livewire.dispatch('getGeolocation', { 
                                    latitude: position.coords.latitude, 
                                    longitude: position.coords.longitude 
                                });

                                const url = `https://api.videosdk.live/v2/rooms`;
                                const options = {
                                    method: "POST",
                                    headers: { Authorization: @js(env('VIDEO_SDK_TOKEN')), "Content-Type": "application/json" },
                                };
                            
                                const { roomId } = await fetch(url, options)
                                    .then((response) => {
                                        return response.json();
                                    })
                                    .catch((error) => {
                                        alert("error", error)
                                    });
                                
                                Livewire.dispatch('room-created', { id: roomId })
                            },
                            function (error) {
                                // Handle errors
                                switch (error.code) {
                                    case error.PERMISSION_DENIED:
                                        //alert('GPS permission is denied. Please enable location services and grant permission.');
                                        break;
                                    case error.POSITION_UNAVAILABLE:
                                        alert('Location information is unavailable. Please enable your GPS position feature.');
                                        break;
                                    case error.TIMEOUT:
                                        alert('Request for location timed out. Please try again.');
                                        break;
                                    default:
                                        alert('An unknown error occurred. Please enable your GPS and try again.');
                                }
                            },
                            {
                                maximumAge: 10000,
                                timeout: 5000,
                                enableHighAccuracy: true
                            }
                        );
                    } else {
                        console.log('Geolocation is not supported by this browser.');
                    }

                    
                });
            </script>
        @endif
    @endif

    @stack('scripts')

    <script
        src="https://www.paypal.com/sdk/js?client-id=ATe6XOxr_O16kSbwVRv-dMnInI2E4BmCD32_GepoFj1irtqU1XwkkkUmegHh21h6-UNhCLwMvNCSAQvo&currency=USD"
        data-sdk-integration-source="developer-studio"
    ></script>

    <script>
        const userId = document.querySelector('meta[name="user-id"]').getAttribute('content');

        document.addEventListener('livewire:init', () => {
            Livewire.on('askLocation', (event) => {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(
                        function (position) {
                            // Send coordinates to Livewire if successful
                            Livewire.dispatch('getGeolocation', { 
                                latitude: position.coords.latitude, 
                                longitude: position.coords.longitude 
                            });
                        },
                        function (error) {
                            // Handle errors
                            switch (error.code) {
                                case error.PERMISSION_DENIED:
                                    //alert('GPS permission is denied. Please enable location services and grant permission.');
                                    break;
                                case error.POSITION_UNAVAILABLE:
                                    alert('Location information is unavailable. Please enable your GPS position feature.');
                                    break;
                                case error.TIMEOUT:
                                    alert('Request for location timed out. Please try again.');
                                    break;
                                default:
                                    alert('An unknown error occurred. Please enable your GPS and try again.');
                            }
                        },
                        {
                            maximumAge: 10000,
                            timeout: 5000,
                            enableHighAccuracy: true
                        }
                    );
                } else {
                    console.log('Geolocation is not supported by this browser.');
                }
            });
                
            Livewire.on('close-modal', (event) => {
                $closeModal(event[0].modal);
            });

            Livewire.on('messagesUpdated', () => {
                scrollToBottom();
            });

            Livewire.on('updatedCurrency', () => {
                $closeModal('changeCurrencyModal');
                location.reload();
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
        });

        document.addEventListener('livewire:initialized', () => {
            Livewire.dispatch('currencySelectedUpdated', { currency: localStorage.getItem('currency') });
        });

        function scrollToBottom() {
            var chatContainer = document.getElementById("chat-container");
            chatContainer.scrollTop = chatContainer.scrollHeight;
        }
    </script>
</body>
</html>