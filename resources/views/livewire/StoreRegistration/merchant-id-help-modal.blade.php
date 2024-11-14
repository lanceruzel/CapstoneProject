<x-modal-card name="merchantHelpIdModal" title="Information" align='center' blurless wire:ignore.self>
    <div class="w-full flex flex-col items-center justify-center">
        <div class="max-w-4xl p-8">
            <h1 class="text-3xl font-bold text-center mb-8">How to Get Your PayPal Merchant ID</h1>
        
            <ol class="space-y-6">
                <li class="flex items-start">
                    <span class="flex-shrink-0 w-8 h-8 flex items-center justify-center rounded-full bg-blue-500 text-white font-bold mr-4">1</span>
                    <div>
                        <h2 class="text-xl font-semibold mb-2">Log in to your PayPal account</h2>
                        <p class="text-gray-600">Go to the PayPal website and click on the "Log In" button in the top right corner. Enter your email address and password to access your account.</p>
                    </div>
                </li>
                
                <li class="flex items-start">
                    <span class="flex-shrink-0 w-8 h-8 flex items-center justify-center rounded-full bg-blue-500 text-white font-bold mr-4">2</span>
                    <div>
                        <h2 class="text-xl font-semibold mb-2">Navigate to your account settings</h2>
                        <p class="text-gray-600">Once logged in, click on the gear icon or "Settings" link, usually located in the top right corner of the dashboard.</p>
                    </div>
                </li>
                
                <li class="flex items-start">
                    <span class="flex-shrink-0 w-8 h-8 flex items-center justify-center rounded-full bg-blue-500 text-white font-bold mr-4">3</span>
                    <div>
                        <h2 class="text-xl font-semibold mb-2">Access your business information</h2>
                        <p class="text-gray-600">In the settings menu, look for "Business Information," "Business Profile," or a similar option. Click on it to view your business details.</p>
                    </div>
                </li>
                
                <li class="flex items-start">
                    <span class="flex-shrink-0 w-8 h-8 flex items-center justify-center rounded-full bg-blue-500 text-white font-bold mr-4">4</span>
                    <div>
                        <h2 class="text-xl font-semibold mb-2">Locate your merchant ID</h2>
                        <p class="text-gray-600">Your merchant ID should be listed on this page. It's typically a 13-character alphanumeric code starting with your country's 2-letter code (e.g., US, UK, DE).</p>
                    </div>
                </li>
                
                <li class="flex items-start">
                    <span class="flex-shrink-0 w-8 h-8 flex items-center justify-center rounded-full bg-blue-500 text-white font-bold mr-4">5</span>
                    <div>
                        <h2 class="text-xl font-semibold mb-2">Copy your merchant ID</h2>
                        <p class="text-gray-600">Once you've found your merchant ID, copy it for your records or to use in your integration with other services.</p>
                    </div>
                </li>
            </ol>
        
            <div class="mt-8 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                <p class="text-sm text-yellow-800">
                    <strong>Note:</strong> If you can't find your merchant ID, it's possible that you have a personal account instead of a business account. In this case, you may need to upgrade to a PayPal Business account to obtain a merchant ID.
                </p>
            </div>
        </div>
    </div>

    <x-slot name="footer" class="flex justify-end gap-x-4">
        <x-button wire:loading.attr="disabled" flat label="Close" x-on:click="close" />
    </x-slot>
</x-modal-card>