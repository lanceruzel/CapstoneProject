<div>
    @if($livestream)
        <div class="flex items-center justify-center gap-3">
            <div class="flex flex-col justify-center items-center gap-3">
                <button class="text-2xl hover:scale-110 transition-all" wire:click="sendReaction(1)">&#128525;</button> <!-- SMILING FACE WITH HEART-SHAPED EYES -->
                {{-- <x-badge rounded="sm" primary :label="$livestream->getReactionCount(1)" /> --}}
            </div>

            <div class="flex flex-col justify-center items-center gap-3">
                <button class="text-2xl hover:scale-110 transition-all" wire:click="sendReaction(2)">&#128514;</button> <!-- FACE WITH TEARS OF JOY -->
                {{-- <p class="font-semibold">x{{ $livestream->getReactionCount(2) }}</p> --}}
            </div>

            <div class="flex flex-col justify-center items-center gap-3">
                <button class="text-2xl hover:scale-110 transition-all" wire:click="sendReaction(3)">&#128545;</button> <!-- POUTING FACE -->
                {{-- <p class="font-semibold">x{{ $livestream->getReactionCount(3) }}</p> --}}
            </div>

            <div class="flex flex-col justify-center items-center gap-3">
                <button class="text-2xl hover:scale-110 transition-all" wire:click="sendReaction(4)">&#128558;</button> <!-- FACE WITH OPEN MOUTH -->
                {{-- <p class="font-semibold">x{{ $livestream->getReactionCount(4) }}</p> --}}
            </div>
        </div>
    @endif
</div>
