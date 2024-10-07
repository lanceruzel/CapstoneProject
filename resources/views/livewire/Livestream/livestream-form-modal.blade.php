<x-modal-card name="livestreamFormModal" title="Livestream" width='sm' align='center' x-cloak x-on:close="$dispatch('clearPostFormModalData')" blurless wire:ignore.self>
    <div class="flex flex-col gap-2 items-start text-gray-600">
        <div class="flex flex-col gap-2 w-full">
            <div class="flex justify-between items-center">
                <p class="font-semibold">Title</p>
            </div>

            <x-input wire:model='title' shadowless />
            <x-input wire:model='location' hidden id="location" />
        </div>
        
        <x-slot name="footer" class="flex justify-end gap-x-4">
            <x-button flat label="Cancel" x-on:click="close" />
            <x-button wire:loading.attr="disabled" spinner="getRoomID" id="createMeetingBtn" label="Create Livestream" />
        </x-slot>
    </div>

    @script
        <script>
            if(navigator.geolocation){
                navigator.geolocation.getCurrentPosition(getLocation);
            }else{ 
                x.innerHTML = "Geolocation is not supported by this browser.";
            }
            
            function getLocation(position) {
                // console.log("Latitude: " + position.coords.latitude);
                // console.log("Longitude: " + position.coords.longitude);

                Livewire.dispatch('getGeolocation', { latitude: position.coords.latitude, longitude: position.coords.longitude });
            }
        </script>
    @endscript
</x-modal-card>