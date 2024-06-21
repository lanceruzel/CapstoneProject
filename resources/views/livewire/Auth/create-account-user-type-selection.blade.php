<x-modal-card name="createAccountUserTypeSelectionModal" title="User Type Selection" align='center' x-cloak blurless wire:ignore.self>
    <div class="flex flex-col items-center justify-center gap-2">
        
        <h1 class="text-2xl font-semibold">What are you?</h1>

        <div class="flex gap-5">
            <x-radio name="account" label="Travelpreneur" wire:model="userType" value="{{ App\Enums\UserType::Travelpreneur }}" />
            <x-radio name="account" label="Store Owner" wire:model="userType" value="{{ App\Enums\UserType::Store }}" />
        </div>
        
        <x-slot name="footer" class="flex justify-end gap-x-4">
            <x-button flat label="Cancel" x-on:click="close" />

            <x-button wire:loading.attr="disabled" wire:click="proceed" spinner="proceed" label="Proceed" />
        </x-slot>
    </div>
</x-modal-card>