<x-modal-card name="viewUserInformationModal" title="View User" persistent align='center' x-cloak x-on:close="$dispatch('clearUserDetailsModal')" blurless wire:ignore.self>
    @if($user)
        <div class="flex flex-col gap-2 items-start text-gray-600" wire:target='contact'>
            @if($user->role == App\Enums\UserType::Store)
                <div class="relative overflow-x-auto w-full">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">
                                Account Information
                            </th>
                        </tr>
                    </thead>

                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <tbody>
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    Role
                                </th>
                                <td class="px-6 py-4">
                                    @if($user->role == App\Enums\UserType::Store)
                                        <x-badge md amber flat label="Store Owner" />
                                    @elseif($user->role == App\Enums\UserType::Travelpreneur)
                                        <x-badge md primary flat label="Travelpreneur" />
                                    @else
                                        <x-badge md violet flat label="Content Creator" />
                                    @endif
                                </td>
                            </tr>

                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    Name
                                </th>
                                <td class="px-6 py-4">
                                    {{ $user->name() }}
                                </td>
                            </tr>

                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    Store Address
                                </th>
                                <td class="px-6 py-4">
                                    <span>{{ $user->storeInformation->country . ', ' . $user->storeInformation->state }}</span>
                                </td>
                            </tr>

                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    Email Address
                                </th>
                                <td class="px-6 py-4">
                                    {{ $user->email }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="relative overflow-x-auto w-full mt-5">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">
                                Paypal Information
                            </th>
                        </tr>
                    </thead>

                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <tbody>
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    Account Name
                                </th>
                                <td class="px-6 py-4">
                                    {{ $user->storeInformation->paypal_merchant_id ? $user->storeInformation->paypal_merchant_id : 'None'}}
                                </td>
                            </tr>
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    Paypal Email
                                </th>
                                <td class="px-6 py-4">
                                    {{ $user->storeInformation->paypal_email ? $user->storeInformation->paypal_email : 'None' }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="relative overflow-x-auto w-full mt-5">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">
                                Store Information
                            </th>
                        </tr>
                    </thead>

                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <tbody>
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    Total Products
                                </th>
                                <td class="px-6 py-4 flex items-center gap-3">
                                    <span>x{{ count($user->products) }}</span> <x-button flat label="View Products" x-on:click="$openModal('viewProductsModal')" wire:click="$dispatch('viewProducts', { id: {{ $user->id }} })"/>
                                </td>
                            </tr>

                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    Total Orders
                                </th>
                                <td class="px-6 py-4 flex items-center gap-3">
                                    <span>x{{ $user->ordered()->count() }}</span> <x-button flat label="View Orders" x-on:click="$openModal('viewOrdersModal')" wire:click="$dispatch('viewOrders', { id: {{ $user->id }} })"/>
                                </td>
                            </tr>

                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    Total Affiliates
                                </th>
                                <td class="px-6 py-4 flex items-center gap-3">
                                    <span>x{{ $user->affiliates()->count() }}</span> <x-button flat label="View Affiliates" x-on:click="$openModal('viewAffiliates')" wire:click="$dispatch('viewAffiliates', { id: {{ $user->id }}, mode: 'store' })" />
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            @elseif($user->role == App\Enums\UserType::Travelpreneur)
                <div class="relative overflow-x-auto w-full">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">
                                Account Information
                            </th>
                        </tr>
                    </thead>

                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <tbody>
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    Role
                                </th>
                                <td class="px-6 py-4">
                                    @if($user->role == App\Enums\UserType::Store)
                                        <x-badge md amber flat label="Store Owner" />
                                    @elseif($user->role == App\Enums\UserType::Travelpreneur)
                                        <x-badge md primary flat label="Travelpreneur" />
                                    @else
                                        <x-badge md violet flat label="Content Creator" />
                                    @endif
                                </td>
                            </tr>

                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    Name
                                </th>
                                <td class="px-6 py-4">
                                    {{ $user->name() }}
                                </td>
                            </tr>

                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    Gender
                                </th>
                                <td class="px-6 py-4">
                                    {{ $user->userInformation->gender }}
                                </td>
                            </tr>

                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    Birthdate
                                </th>
                                <td class="px-6 py-4">
                                    {{ $user->userInformation->birthdate }}
                                </td>
                            </tr>

                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    @if($user->role != App\Enums\UserType::Store)
                                        <span>Address</span>
                                    @else
                                        <span>Store Address</span>
                                    @endif
                                </th>
                                <td class="px-6 py-4">
                                    <span>{{ $user->userInformation->country . ', ' . $user->userInformation->state }}</span>
                                </td>
                            </tr>

                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    Email Address
                                </th>
                                <td class="px-6 py-4">
                                    {{ $user->email }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="relative overflow-x-auto w-full mt-5">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">
                                Paypal Information
                            </th>
                        </tr>
                    </thead>

                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <tbody>
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    Account Name
                                </th>
                                <td class="px-6 py-4">
                                    {{ $user->storeInformation->paypal_merchant_id ? $user->storeInformation->paypal_merchant_id : 'None'}}
                                </td>
                            </tr>
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    Paypal Email
                                </th>
                                <td class="px-6 py-4">
                                    {{ $user->storeInformation->paypal_email ? $user->storeInformation->paypal_email : 'None' }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="relative overflow-x-auto w-full mt-5">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">
                                Store Information
                            </th>
                        </tr>
                    </thead>

                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <tbody>
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    Total Products
                                </th>
                                <td class="px-6 py-4 flex items-center gap-3">
                                    <span>x{{ count($user->products) }}</span> <x-button flat label="View Products" x-on:click="$openModal('viewProductsModal')" wire:click="$dispatch('viewProducts', { id: {{ $user->id }} })"/>
                                </td>
                            </tr>

                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    Total Orders
                                </th>
                                <td class="px-6 py-4 flex items-center gap-3">
                                    <span>x{{ $user->ordered()->count() }}</span> <x-button flat label="View Orders" x-on:click="$openModal('viewOrdersModal')" wire:click="$dispatch('viewOrders', { id: {{ $user->id }} })"/>
                                </td>
                            </tr>

                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    Total Affiliates
                                </th>
                                <td class="px-6 py-4 flex items-center gap-3">
                                    <span>x{{ $user->affiliates()->count() }}</span> <x-button flat label="View Affiliates" x-on:click="$openModal('viewAffiliates')" wire:click="$dispatch('viewAffiliates', { id: {{ $user->id }}, mode: 'store' })" />
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            @else
                <div class="relative overflow-x-auto w-full">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">
                                Account Information
                            </th>
                        </tr>
                    </thead>

                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <tbody>
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    Role
                                </th>
                                <td class="px-6 py-4">
                                    @if($user->role == App\Enums\UserType::Store)
                                        <x-badge md amber flat label="Store Owner" />
                                    @elseif($user->role == App\Enums\UserType::Travelpreneur)
                                        <x-badge md primary flat label="Travelpreneur" />
                                    @else
                                        <x-badge md violet flat label="Content Creator" />
                                    @endif
                                </td>
                            </tr>

                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    Name
                                </th>
                                <td class="px-6 py-4">
                                    {{ $user->name() }}
                                </td>
                            </tr>

                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    Gender
                                </th>
                                <td class="px-6 py-4">
                                    {{ $user->userInformation->gender }}
                                </td>
                            </tr>

                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    Birthdate
                                </th>
                                <td class="px-6 py-4">
                                    {{ $user->userInformation->birthdate }}
                                </td>
                            </tr>

                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    @if($user->role != App\Enums\UserType::Store)
                                        <span>Address</span>
                                    @else
                                        <span>Store Address</span>
                                    @endif
                                </th>
                                <td class="px-6 py-4">
                                    <span>{{ $user->userInformation->country . ', ' . $user->userInformation->state }}</span>
                                </td>
                            </tr>

                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    Email Address
                                </th>
                                <td class="px-6 py-4">
                                    {{ $user->email }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="relative overflow-x-auto w-full mt-5">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">
                                Affiliate Information
                            </th>
                        </tr>
                    </thead>

                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <tbody>
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    Total Affiliated Stores
                                </th>
                                <td class="px-6 py-4 flex items-center gap-3">
                                    <td class="px-6 py-4 flex items-center gap-3">
                                        <span>x{{ $user->affiliates()->count() }}</span> <x-button flat label="View Affiliates" x-on:click="$openModal('viewAffiliates')" wire:click="$dispatch('viewAffiliates', { id: {{ $user->id }}, mode: 'content' })" />
                                    </td>
                                </td>
                            </tr>

                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    Total Earnings
                                </th>
                                <td class="px-6 py-4 flex items-center gap-3">
                                    <span>${{ number_format($user->totalAffiliateEarnings(), 2) }}
                                </td>
                            </tr>

                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    Total Unclaimed
                                </th>
                                <td class="px-6 py-4 flex items-center gap-3">
                                    <span>${{ number_format($user->totalUnclaimedCommissions(), 2) }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            @endif

            <x-slot name="footer" class="flex justify-end gap-x-4">
                <x-button wire:loading.attr="disabled" flat label="Close" x-on:click="close" />
            </x-slot>
        </div>
    @else
        <div class="flex items-center justify-center">
            <div class="flex items-row items-center justify-center gap-3">
                <x-icon name='arrow-path' class="h-5 w-5 animate-spin"/>

                <span>
                    Fetching Data...
                </span>
            </div>
        </div>
    @endif
</x-modal-card>