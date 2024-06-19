<div class="w-full space-y-5">
    @foreach ($posts as $key => $post)
        <livewire:Posting.post-container :post="$post" id='{{ $key }}-post1-{{ $post->id }}' wire:key='{{ $key }}-post2-{{ $post->id }}' />
    @endforeach
</div>