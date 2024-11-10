<div class="p-5 max-lg:border max-lg:shadow max-lg:bg-white rounded-lg space-y-3 w-full max-w-[600px]">
    <h1 class="text-3xl font-bold">Store sign up</h1>

    <x-input class="col-span-2" label="Name of your store" wire:model="name" shadowless />

    <div class="grid grid-cols-2 gap-3">
        <x-select
            class="max-lg:col-span-2"
            label="What country does your store located?"
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

    <x-phone
        class="col-span-2"
        shadowless
        wire:model="contact"
        label="Contact Number"
        :mask="[
            '(###) ###-####',               // US format
            '+# ### ###-####',              // Generic international format
            '+## ## ####-####',             // Europe format
            '+### ## ### ####',             // UK and other similar regions
            '+## (##) ####-####',           // Canada format
            '+## (#) ####-####'             // General international format
        ]"
    />

    <x-input label="Username" wire:model='username' corner="Ex: pchub1202" shadowless />
    <x-input label="Email" wire:model='email' shadowless />

    <div class="grid grid-cols-2 gap-3">
        <x-password class="max-lg:col-span-2" label="Password" wire:model="password" shadowless />
        <x-password class="max-lg:col-span-2" label="Confirm Password" wire:model="password_confirmation" shadowless />
    </div>

    <x-button wire:loading.attr="disabled" class="w-full" wire:click="signup" spinner="signup" label="Sign up" />

    <div class="w-full text-center">
        <x-link label="Already have an account?" :href="route('login')" secondary sm />
    </div>
</div>