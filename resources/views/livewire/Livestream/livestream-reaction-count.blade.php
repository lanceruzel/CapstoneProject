<div {{ $noPoll ? '' : 'wire:poll.5000ms' }}>
    @if($livestream)
        <div class="flex items-center justify-center gap-3 py-2 border-b">
            <div class="flex flex-col justify-center items-center">
                <button class="text-2xl">&#128525;</button> <!-- SMILING FACE WITH HEART-SHAPED EYES -->
                <x-badge rounded flat slate :label="$livestream->getReactionCount(1)" />
            </div>

            <div class="flex flex-col justify-center items-center">
                <button class="text-2xl">&#128514;</button> <!-- FACE WITH TEARS OF JOY -->
                <x-badge rounded flat slate :label="$livestream->getReactionCount(2)" />
            </div>

            <div class="flex flex-col justify-center items-center">
                <button class="text-2xl">&#128545;</button> <!-- POUTING FACE -->
                <x-badge rounded flat slate :label="$livestream->getReactionCount(3)" />
            </div>

            <div class="flex flex-col justify-center items-center">
                <button class="text-2xl">&#128558;</button> <!-- FACE WITH OPEN MOUTH -->
                <x-badge rounded flat slate :label="$livestream->getReactionCount(4)" />
            </div>
        </div>
    @endif
</div>
