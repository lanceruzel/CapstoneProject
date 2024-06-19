<div class="w-full space-y-5">
    @if($posts->count() === 0)
        <div class="flex items-center justify-center w-full h-full">
            <img src="{{ asset('assets/svg/no-data-1.svg') }}" alt="No data found"/>
        </div>
    @else
        @foreach ($posts as $key => $post)
            <livewire:Posting.post-container :post="$post" wire:key="{{ $key }}-status-{{ $post->id }}">
        @endforeach 
    @endif
</div>