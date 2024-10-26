<div class="p-5 max-lg:border max-lg:shadow max-lg:bg-white rounded-lg space-y-3 w-full max-w-[400px]">
    <h1 class="text-3xl font-bold">Reset Password</h1>

    @if(session()->has('status'))
        <x-alert :title="session('status')" positive />
    @endif

    @if(session()->has('errors'))
        <x-alert title="{!! session('errors')->first('email') !!}" negative />
    @endif

    <x-input class="hidden" label="token" wire:model="token" shadowless />

    <x-input type="email" label="Email" wire:model="email" shadowless />
    <x-password label="Password" wire:model="password" shadowless />
    <x-input type="password" label="Confirm Password" wire:model="password_confirmation" shadowless />

    <x-button wire:loading.attr="disabled" class="w-full" wire:click="resetPassword" spinner="resetPassword" label="Reset Password" />
</div>