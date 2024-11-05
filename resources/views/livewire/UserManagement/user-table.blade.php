<div class="bg-white shadow rounded-lg p-5">
    <div class="flex items-center justify-between">
        <p class="font-semibold text-xl">User lists</p>

        <div class="w-60 flex items-center justify-center gap-3">
            <x-dropdown>
                <x-slot name="trigger">
                    <x-mini-button rounded icon="funnel" flat gray interaction="gray" />
                </x-slot>
             
                <x-dropdown.header label="Filter">
                    <x-dropdown.item>
                        <x-checkbox label="Content Creator" wire:model.live="filterStatus" :value="App\Enums\UserType::ContentCreator" />
                    </x-dropdown.item>

                    <x-dropdown.item>
                        <x-checkbox label="Store Owner" wire:model.live="filterStatus" :value="App\Enums\UserType::Store" />
                    </x-dropdown.item>

                    <x-dropdown.item>
                        <x-checkbox label="Travelpreneur" wire:model.live="filterStatus" :value="App\Enums\UserType::Travelpreneur" />
                    </x-dropdown.item>
                </x-dropdown.header>
            </x-dropdown>

            <x-input icon="magnifying-glass" wire:model.live.debounce.200ms="search" placeholder="Search" shadowless />
        </div>
    </div>

    <div class="w-full pt-5 overflow-auto flex items-center justify-center flex-col">
        <table class="table-auto w-full border-spacing-y-4 text-sm text-left">
            <thead class="border-b-2">
                <tr>
                    <th scope="col" class="px-6 py-3">Name</th>
                    <th scope="col" class="px-6 py-3">Role</th>
                    <th scope="col" class="px-6 py-3">Email</th>
                    <th scope="col" class="px-6 py-3">Country</th>
                    <th scope="col" class="px-6 py-3">Created At</th>
                    <th scope="col" class="px-6 py-3"></th>
                </tr>
            </thead>

            <tbody>
                @if($users && count($users) > 0)
                    @foreach ($users as $user)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-100">
                            <td class="px-6 py-4">{{ $user->name() }}</td>
                            <td class="px-6 py-4">
                                @if($user->role == App\Enums\UserType::ContentCreator)
                                    <x-badge flat violet label="Content Creator" />
                                @elseif($user->role == App\Enums\UserType::Travelpreneur)
                                    <x-badge flat primary label="Travelpreneur" />
                                @elseif($user->role == App\Enums\UserType::Store)
                                    <x-badge flat amber label="Store Owner" />
                                @else
                                    <x-badge flat negative label="Unknown" />
                                @endif
                            </td>
                            <td class="px-6 py-4">{{ $user->email }}</td>
                            <td class="px-6 py-4">
                                @if($user->role == App\Enums\UserType::Store)
                                    <span>{{ $user->storeInformation->country }}</span>
                                @else
                                    <span>{{ $user->userInformation->country }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">{{ date_format($user->created_at, "M d, Y") }}</td>
                            <td class="-mr-1 px-6 py-4">
                                <x-button label="View" flat interaction:solid="info" x-on:click="$openModal('viewUserInformationModal')" wire:click="$dispatch('viewUserDetails', { id: {{ $user->id }} })"/>
                            </td>
                        </tr>
                    @endforeach 
                @endif
            </tbody>
        </table>

        @if(count($users) <= 0)
            <div class="flex flex-col items-center justify-center mt-5">
                <h1 class="text-2xl font-semibold">No records found</h1>
                <img class="h-[400px]" src="{{ asset('assets/svg/no-data-2.svg') }}" alt="No data found"/>
            </div>
        @endif
    </div>

    <!-- Pagination -->
    <div class="w-full mt-5">
        {{ $users->links() }}
    </div>
</div>