<x-modal-card name="updatePasswordModal" title="Update Password" width="md" align='center' x-cloak x-on:close="$dispatch('clearUpdatePasswordModal')" blurless wire:ignore.self>  
    <div class="flex flex-col gap-2 items-start text-gray-600 overflow-auto">
        <div class="space-y-2 w-full">
            <x-input type="password" label="Current Password" wire:model="currentPassword" shadowless />
            <x-password label="New Password" wire:model="password" shadowless />
            <x-input type="password" label="Confirm Password" wire:model="password_confirmation" shadowless />
        </div>
        
        <x-slot name="footer" class="flex justify-end gap-x-4">
            <x-button flat label="Cancel" x-on:click="close" />
            <x-button wire:loading.attr="disabled" wire:click="confirmation" spinner="updatePassword" label="Change Password" />
        </x-slot>
    </div>
</x-modal-card>