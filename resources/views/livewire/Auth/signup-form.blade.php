<div>
    <div class="p-5 max-lg:border max-lg:shadow max-lg:bg-white rounded-lg space-y-3 w-full max-w-[600px]">
        <h1 class="text-3xl font-bold">Sign up</h1>

        <div class="grid grid-cols-2 gap-3">
            <x-input class="max-lg:col-span-2" label="First Name" wire:model.defer="firstName" shadowless />
            <x-input class="max-lg:col-span-2" label="Last Name" wire:model.defer="lastName" shadowless />
            <x-datetime-picker class="max-lg:col-span-2" label="Birthdate" wire:model.defer="birthdate" without-time shadowless />
            <x-select class="max-lg:col-span-2" label="Gender" wire:model.defer="gender" placeholder="Select Gender" :options="['Male', 'Female']" shadowless />
        </div>

        <div class="grid grid-cols-2 gap-3">
            <x-select
                class="max-lg:col-span-2"
                label="Country"
                wire:model="country"
                placeholder="Select Country"
                :options="$countryOptions"
                option-label="name"
                option-value="value"
                searchable
                shadowless
                x-on:selected="Livewire.dispatch('updatedCountry')"
            />

            <x-select
                class="max-lg:col-span-2"
                label="State"
                wire:model="state"
                placeholder="Select State"
                :options="$stateOptions"
                option-label="name"
                option-value="value"
                searchable
                shadowless
            />
        </div>

        <x-input label="Username" wire:model.defer="username" shadowless />
        <x-input label="Email" wire:model.defer="email" shadowless />

        <div class="grid grid-cols-2 gap-3">
            <x-password class="max-lg:col-span-2" label="Password" wire:model.defer="password" shadowless />
            <x-input type="password" class="max-lg:col-span-2" label="Confirm Password" wire:model.defer="password_confirmation" shadowless />
        </div>

        <x-button wire:loading.attr="disabled" class="w-full" wire:click="signup" spinner="signup" label="Sign up" />

        <div class="w-full text-center">
            <x-link label="Already have an account?" :href="route('login')" secondary sm />
        </div>
    </div>
</div>