<div class="p-5 max-lg:border max-lg:shadow max-lg:bg-white rounded-lg space-y-3 w-full max-w-[400px]">
    <h1 class="text-3xl font-bold">Login</h1>

    @if(session()->has('success'))
        <x-alert :title="session('success')" positive />
    @endif

    @if(session()->has('fail'))
        <x-alert :title="session('fail')" negative />
    @endif

    <x-input icon="envelope" label="Email" wire:model='email' shadowless />
    <x-password icon="lock-closed" label="Password" wire:model='password' shadowless />
    <x-button wire:loading.attr='disabled' class="w-full" wire:click='signin' spinner='signin' label="Sign in" />

    <div class="w-full flex items-center justify-center flex-col">
        <div class="w-full flex items-center justify-center py-3 ">
            <span class="w-full border border-b border-gray-300"></span>
            <span class="px-4 font-semibold text-gray-600">or</span>
            <span class="w-full border border-b border-gray-300"></span>
        </div>
        <x-link label="Create an account" href="{{ route('signup') }}" secondary sm />

        <x-link class="mt-3" label="Do you plan on selling a product?" href="{{ route('signin') }}" secondary sm />
    </div>
</div>