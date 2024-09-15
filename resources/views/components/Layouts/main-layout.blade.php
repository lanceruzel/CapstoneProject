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

    <main x-bind:class="sidebarOpened ? 'md:!ps-64' : ''" x-transition class="{{ request()->routeIS('message') ? 'lg:ps-64' : 'md:ps-64' }} min-h-screen pt-16 bg-gray-50 transition-all pb-16 md:pb-0">
        <div class="p-5">
            {{ $slot }}
        </div>
    </main>

    <livewire:Product.product-view-modal />
    <livewire:Product.product-view-variation-selection-modal />

    @if(auth()->user()->role == App\Enums\UserType::Store || auth()->user()->role == App\Enums\UserType::Travelpreneur)
        <livewire:StoreRegistration.store-register-form-modal />
    @else
        <livewire:Affiliate.affiliate-dashboard-modal />
        <livewire:Affiliate.affiliate-invitation-modal />
        <livewire:Affiliate.view-terms-and-condition-modal />
    @endif

    @if(request()->routeIS('livestream') || request()->routeIS('home'))
        <script src="https://sdk.videosdk.live/js-sdk/0.0.67/videosdk.js"></script>
        <script src="{{ asset('livestreamScripts/config.js') }}"></script>

        <!-- hls lib script  -->
        <script src="https://cdn.jsdelivr.net/npm/hls.js"></script>

        @if(request()->routeIS('home'))
            <script>
                const createButton = document.getElementById("createMeetingBtn");

                createButton.addEventListener("click", async () => {
                    const url = `https://api.videosdk.live/v2/rooms`;
                    const options = {
                    method: "POST",
                    headers: { Authorization: TOKEN, "Content-Type": "application/json" },
                    };
                
                    const { roomId } = await fetch(url, options)
                    .then((response) => response.json())
                    .catch((error) => alert("error", error));
                    meetingId = roomId;
                
                    // initializeMeeting(Constants.modes.CONFERENCE);
                
                    Livewire.dispatch('room-created', { id: meetingId })
                });
            </script>
        @endif
    @endif
    
    <script>
        const userId = document.querySelector('meta[name="user-id"]').getAttribute('content');

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

        function scrollToBottom() {
            var chatContainer = document.getElementById("chat-container");
            chatContainer.scrollTop = chatContainer.scrollHeight;
        }

        // Call scrollToBottom when the page loads
        window.onload = function() {
            scrollToBottom();
        }
    </script>

    @stack('scripts')
</body>
</html>