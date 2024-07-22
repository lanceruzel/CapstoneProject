<div class="w-full space-y-5">
    {{-- @if($posts->count() === 0)
        <div class="flex items-center justify-center w-full h-full">
            <img src="{{ asset('assets/svg/no-data-1.svg') }}" alt="No data found"/>
        </div>
    @else
        @foreach ($posts as $key => $post) --}}
        @for ($i = 0; $i < 5; $i++)
            <livewire:Livestream.livestream-post-container wire:key="{{ $i }}-livestream-{{ $i }}">
        @endfor
        {{-- @endforeach 
    @endif --}}
</div>