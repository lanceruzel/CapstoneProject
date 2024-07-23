<div class="w-full space-y-5">
    @if($livestreams->count() === 0)
        <div class="flex items-center justify-center w-full h-full">
            <img src="{{ asset('assets/svg/no-data-1.svg') }}" alt="No data found"/>
        </div>
    @else
        @foreach ($livestreams as $key => $livestream)
            <livewire:Livestream.livestream-post-container :livestream="$livestream" wire:key="{{ $key }}-livestream-{{ $livestream }}">
        @endforeach 
    @endif
</div>