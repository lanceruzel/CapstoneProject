<x-modal-card name="createAccountUserTypeSelectionModal" title="User Type Selection" align='center' x-cloak blurless wire:ignore.self>
    <div class="flex flex-col items-center justify-center gap-2">
        
        <h1 class="text-2xl font-semibold">What are you?</h1>

        <x-button flat black label="I am a traveller that sells product from other country" href="{{ route('signup', App\Enums\UserType::Travelpreneur) }}" />

        <p class="text-sm">or</p>

        <x-button flat black label="I am a store owner" href="{{ route('store-signup', App\Enums\UserType::Travelpreneur) }}" />
    </div>
</x-modal-card>