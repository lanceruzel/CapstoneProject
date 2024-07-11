<!-- Post Sample -->
<div class="w-full border p-5 rounded-lg bg-white">
    <div wire:ignore class="flex flex-row justify-between items-center gap-3">
        <div class="flex gap-2 items-center">
            <div class="size-10">
                <div class="w-full h-full bg-red-500 rounded-full"></div>
            </div>

            <div class="leading-none">
                <div class="flex items-center justify-start gap-1">
                    <a href="{{ route('profile', $post->user->username) }}" class="hover:text-gray-700 hover:no-underline py-0 font-medium">{{ $post->user->role == App\Enums\UserType::Store ? $post->user->storeInformation->name : $post->user->userInformation->fullname() }}</a>
                </div>

                <small class="text-xs font-medium text-gray-600">{{ $this->getDateTimeDiff() }}</small>
            </div>
        </div>

        @if($post->user->id == Auth::id())
            <x-dropdown position="bottom">
                <x-dropdown.item label="Update Post" wire:click="$dispatch('update-post', { id: {{ $post->id }} })" />
                <x-dropdown.item label="Delete Post" wire:click="$dispatch('delete-post', { id: {{ $post->id }} })" />
            </x-dropdown>
        @endif
    </div>

    <div class="leading-snug h-auto text-wrap text-ellipsis break-words hyphens-auto mt-3">
        {!! nl2br($post->content) !!}
    </div>

    <div wire:ignore>
        @if(json_decode($post->images) != null)
            @if(count(json_decode($post->images)) === 1)
                <!-- post image -->
                <div class="relative w-full h-full" uk-lightbox>
                    <a href="{{ asset('uploads/posts') . '/' . json_decode($post->images)[0] }}">
                        <img src="{{ asset('uploads/posts') . '/' . json_decode($post->images)[0] }}" alt="" class="sm:rounded-lg w-full h-full object-cover">
                    </a>
                </div>
            @elseif(count(json_decode($post->images)) > 1)
                <!-- slide images -->
                <div class="relative uk-visible-toggle uk-slideshow w-full" tabindex="-1" uk-slideshow="animation: push;finite: true;min-height: 300; max-height: 350">

                    <ul class="uk-slideshow-items" uk-lightbox="" style="min-height: 350px;">
                        @foreach(json_decode($post->images) as $image)
                            <li class="w-full sm:rounded-md" tabindex="-1" style="">
                                <a href="{{ asset('uploads/posts') . '/' . $image }}">
                                    <img src="{{ asset('uploads/posts') . '/' . $image }}" class="w-full h-full object-cover inset-0" alt="">
                                </a>
                            </li>
                        @endforeach
                    </ul>

                    <!-- navigation -->
                    <button type="button" class="absolute -left-3 -translate-y-1/2 bg-gray-100/50 backdrop-blur-xl rounded-full top-1/2 grid w-8 h-7 place-items-center border" uk-slideshow-item="previous">
                        <x-icon name="chevron-left" class="w-5 h-5" />
                    </button>

                    <button type="button" class="absolute -right-3 -translate-y-1/2 bg-gray-100/50 backdrop-blur-xl rounded-full top-1/2 grid w-8 h-7 place-items-center border uk-invisible" uk-slideshow-item="next">
                        <x-icon name="chevron-right" class="w-5 h-5" />
                    </button>
                </div>
            @endif
        @endif
    </div>

    <!-- Post Actions -->
    <div class="text-gray-600 w-full py-4">
        @if($post->type == App\Enums\PostType::Product)
            <div class="flex flex-col justify-center items-center mb-4 gap-3">
                <button class="group flex gap-1 items-center justify-center rounded-lg text-sm font-medium w-full text-blue-500 active:scale-95 transition-all">
                    View Product
                </button>
            </div>
        @endif

        <div class="flex items-center justify-around h-8">
            <button wire:click='storeLike' class="group flex gap-1 items-center justify-center rounded-lg active:scale-110 transition-all text-sm font-medium h-full">
                
                @if($hasLike)
                    <x-icon name="heart" solid/>  
                @else
                    <x-icon name="heart" />  
                @endif
                

                <span>{{ $totalLikes }} Likes</span>
            </button>
    
            <button class="group flex gap-1 items-center justify-center rounded-lg text-sm font-medium h-full" x-on:click="showAllComments = ! showAllComments">
                <x-icon name="chat-bubble-left-right" />  
                    
                <span>{{ $totalComments }} Comments</span>
            </button>
        </div>
    </div>

    <div>
        @if($post->postComments->count() > 0)
            <div class="border-y py-4" x-data="{ showAllComments: @entangle('showAllComments') }">
                <div class="space-y-3">
                    @foreach ($comments as $key => $comment)
                        <livewire:Posting.comment-container :comment="$comment" wire:key="{{ $key }}-comment-{{ $comment->id }}-post-{{ $post->id }}" />
                    @endforeach
                    
                    @if($post->postComments->count() > 3)
                        <div class="text-gray-500 text-sm cursor-pointer mt-3" wire:click="toggleComments" wire:loading.attr='disabled'>
                            <x-button x-show="!showAllComments" icon='chevron-down' x-cloak wire:loading.remove wire:target="toggleComments" white label="More comments" />
                            <x-button x-show="showAllComments" icon='chevron-up' x-cloak wire:loading.remove wire:target="toggleComments" white label="Hide comments" />

                            <span wire:loading wire:target="toggleComments">
                                Loading...
                            </span>
                        </div>
                    @endif
                </div>
            </div>
        @else
            <hr>
        @endif
    </div>

    <hr>

    <div class="flex gap-2 flex-row items-center justify-items-center mt-4">
        <div class="size-4 bg-red-500 rounded-full p-4"></div>

        <input placeholder="Add Comment...." class="w-full resize-none text-sm !bg-transparent px-4 py-2 focus:outline-none focus:!border-transparent focus:!ring-transparent" wire:model="commentContent"></input>

        <button class="bg-gray-100 px-4 py-1.5 h-full rounded-full text-sm font-medium active:scale-95 transition-all w-auto hover:bg-gray-200" wire:loading.attr='disabled' wire:click.prevent='storeComment'>
            <span wire:loading.remove wire:target="storeComment">Send</span>
    
            <div wire:loading wire:target="storeComment"> 
                Sending...
            </div>
        </button>
    </div>
</div>