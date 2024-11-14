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

    <script type="module">
        let merchantIDs = [];

        const userId = document.querySelector('meta[name="user-id"]').getAttribute('content');

        document.addEventListener('livewire:init', () => {
            let total = 0;
            let formattedMerchant = null;
            const clientId = 'ATe6XOxr_O16kSbwVRv-dMnInI2E4BmCD32_GepoFj1irtqU1XwkkkUmegHh21h6-UNhCLwMvNCSAQvo';

            Livewire.on('getTotal', (event) => {
                total = event[0].total;
            });

            Livewire.on('orders-merchant', (event) => {
                handleMerchantData(event[0].merchants);
            });

            // Function to load the PayPal SDK
            function loadPayPalScript(merchantIds) {
                return new Promise((resolve, reject) => {
                    const script = document.createElement('script');
                    let baseUrl = `https://www.paypal.com/sdk/js?client-id=${clientId}&currency=USD&disable-funding=credit,card`;

                    if (merchantIds.length === 1) {
                        // console.log(merchantIds[0])
                        // For a single merchant, add the ID directly to the URL
                        baseUrl += `&merchant-id=${merchantIds[0]}`;
                    } else {
                        // For multiple merchants, use merchant-id=* in the URL and set data-merchant-id attribute
                        baseUrl += `&merchant-id=*`;
                        script.setAttribute('data-merchant-id', merchantIds.join(','));
                    }

                    script.src = baseUrl;
                    script.setAttribute('data-sdk-integration-source', 'developer-studio');
                    script.onload = () => resolve();
                    script.onerror = () => reject(new Error('Failed to load PayPal SDK'));
                    document.body.appendChild(script);
                });
            }

            function renderPayPalButtons() {
                paypal.Buttons({
                    createOrder: function(data, actions) {
                        return actions.order.create({
                            purchase_units: formattedMerchant,
                            application_context: {
                                shipping_preference: "NO_SHIPPING"
                            }
                        });
                    },
                    onApprove: function(data, actions) {
                        return actions.order.capture().then(function(details) {
                            Livewire.dispatch('payment-completed', { 
                                status: details.status, 
                                references: details.purchase_units
                            });
                        });
                    },
                }).render('#paypal-button-container').then(() => {
                    document.querySelector('#paypal-button-container').style.pointerEvents = 'none';
                    document.querySelector('#paypal-button-container').style.opacity = '0.5';
                });
            }

            // Function to handle merchant data
            function handleMerchantData(merchants) {
                const convertedToArray = Object.values(merchants);

                formattedMerchant = convertedToArray.map((merchant) => ({
                    amount: {
                        value: (parseFloat(merchant.total.toFixed(2)) + 3 ).toFixed(2),
                        currency_code: 'USD'
                    },
                    payee: {
                        email_address: merchant.seller.store_information.paypal_email
                    },
                    reference_id: merchant.seller.store_information.paypal_merchant_id
                }));

                merchantIDs = formattedMerchant.map((merchant) => merchant.reference_id);

                // Load PayPal SDK and render buttons
                loadPayPalScript(merchantIDs)
                    .then(renderPayPalButtons)
                    .catch(error => console.error('Error loading PayPal SDK:', error));
            }

            function enablePayPalButton() {
                const container = document.querySelector('#paypal-button-container');
                if (container) {
                    container.style.pointerEvents = 'auto';
                    container.style.opacity = '1';
                }
            }

            // Additional Livewire event handlers
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
                        description: 'You have received a new notification!'
                    });
                });

            Livewire.on('enable-paypal-button', function() {
                enablePayPalButton()
            });
        });
    </script>
</body>

</html>