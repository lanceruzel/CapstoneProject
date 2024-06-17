<div class="p-5 max-lg:border max-lg:shadow max-lg:bg-white rounded-lg space-y-3 w-full max-w-[400px]">
    <h1 class="text-3xl font-bold">Login</h1>
    <x-input icon="envelope" label="Email" shadowless />
    <x-password  icon="lock-closed" label="Password" shadowless />
    <x-button wire:loading.attr='disabled' class="w-full" wire:click='login' spinner='login' label="Sign in" />

    <div class="w-full text-center">
        <div class="w-full flex items-center justify-center">
            <span class="w-full border border-b border-gray-300"></span>
            <span class="px-4 font-semibold text-gray-600">or</span>
            <span class="w-full border border-b border-gray-300"></span>
        </div>
        <x-link label="Create an account" href="{{ route('signup') }}" secondary sm />
    </div>
</div>