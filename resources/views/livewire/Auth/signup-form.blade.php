<div class="p-5 max-lg:border max-lg:shadow max-lg:bg-white rounded-lg space-y-3 w-full max-w-[450px]">
    <h1 class="text-3xl font-bold">Sign up</h1>

    <div class="grid grid-cols-2 gap-3">
        <x-input class="max-lg:col-span-2" label="First Name" shadowless />
        <x-input class="max-lg:col-span-2" label="Last Name" shadowless />

        <x-datetime-picker class="max-lg:col-span-2" label="Birthdate" parse-format="DD-MM-YYYY" without-time shadowless />
        <x-select label="Gender" placeholder="Select Gender" :options="['Male', 'Female']" shadowless />
    </div>

    <x-input label="Username" shadowless />
    <x-input label="Email" shadowless />
    <x-password label="Password" shadowless />
    <x-password label="Confirm Password" shadowless />
    <x-button wire:loading.attr='disabled' class="w-full" wire:click='signup' spinner='signup' label="Sign up" />

    <div class="w-full text-center">
        <x-link label="Already have an account?" href="{{ route('signin') }}" secondary sm />
    </div>
</div>