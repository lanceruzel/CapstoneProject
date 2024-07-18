<div class="p-5 max-lg:border max-lg:shadow max-lg:bg-white rounded-lg space-y-3 w-full max-w-[600px]">
    <h1 class="text-3xl font-bold">Sign up</h1>

    <div class="grid grid-cols-2 gap-3">
        <x-input class="max-lg:col-span-2" label="First Name" wire:model="firstName" shadowless />

        <x-input class="max-lg:col-span-2" label="Last Name" wire:model="lastName" shadowless />

        <x-datetime-picker class="max-lg:col-span-2" label="Birthdate" wire:model="birthdate" without-time shadowless />
        <x-select class="max-lg:col-span-2" label="Gender" wire:model.live="gender" placeholder="Select Gender" :options="['Male', 'Female']" shadowless />
    </div>

    <div class="grid grid-cols-2 gap-3">
        <x-select class="max-lg:col-span-2" label="Country" wire:model="country" placeholder="Select Country" :options="$countries" searchable shadowless />
        <x-input class="max-lg:col-span-2" label="Address" wire:model="address" shadowless />
    </div>

    <x-input label="Username" wire:model='username' shadowless />
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