<div class="p-5 max-lg:border max-lg:shadow max-lg:bg-white rounded-lg space-y-3 w-full max-w-[400px]">
    <h1 class="text-3xl font-bold">Forgot Password</h1>

    @if(session()->has('status'))
        <x-alert :title="session('status')" positive />
    @endif

    @if(session()->has('errors'))
        <x-alert title="{!! session('errors')->first('email') !!}" negative />
    @endif

    <x-input icon="envelope" label="Email" wire:model='email' shadowless />

    <x-button wire:loading.attr='disabled' class="w-full" wire:click='sendEmail' spinner='sendEmail' label="Send Email" />

    <div class="w-full text-center">
        <x-link label="Back to sign in?" :href="route('login')" secondary sm />
    </div>
</div>