<!-- Post Sample -->
<div class="w-full border p-5 rounded-lg bg-white">
    <div wire:ignore class="flex flex-row justify-between items-center gap-3">
        <div class="flex gap-2 items-center">
            <div class="size-10 rounded-full relative">
                @if($post->user->profilePicture() == null)
                    <x-icon name="user" solid class="w-full h-full bg-gray-200 rounded-full p-2 border" />
                @else
                    <img src="{{ asset('uploads') . '/' . $post->user->profilePicture() }}" class="w-full h-full object-cover rounded-full border">
                @endif

                @if($post->user->isOnline())
                    <div class="absolute size-3 bg-green-500 rounded-full top-0 right-0"></div>
                @endif
            </div>

            <div class="leading-none">
                <div class="flex items-start justify-start gap-1">
                    <a href="{{ route('profile', $post->user->username) }}" class="hover:text-gray-700 hover:no-underline py-0 font-medium">{{ $post->user->name() }}</a>
                    
                    @switch($post->user->role)
                        @case(App\Enums\UserType::ContentCreator)
                            <x-icon name="cursor-arrow-rays" class="w-4 h-4" solid />
                            @break

                        @case(App\Enums\UserType::Store)
                            <x-icon name="building-storefront" class="w-4 h-4" solid />
                            @break

                        @case(App\Enums\UserType::Travelpreneur)
                            <x-icon name="briefcase" class="w-4 h-4" solid />
                            @break
                    
                        @default
                            <x-icon name="user" class="w-4 h-4" solid />
                            @break
                    @endswitch
                </div>

                <small class="text-xs font-medium text-gray-600">{{ App\Classes\CustomDateTimeFormat::formatAgo($post->created_at) }}</small>
                <small> at {{ $post->country }}</small>
            </div>
        </div>

        @if($post->user->id == Auth::id())
            <x-dropdown position="bottom">
                <x-dropdown.item label="Update Post" wire:click="$dispatch('update-post', { id: {{ $post->id }} })" />
                <x-dropdown.item label="Delete Post" wire:click="$dispatch('delete-post', { id: {{ $post->id }} })" />
            </x-dropdown>
        @endif
    </div>

    <div class="ml-2 leading-snug h-auto text-wrap text-ellipsis break-words hyphens-auto mt-3">
        {!! nl2br($post->content) !!}
    </div>

    <div wire:ignore class="mt-3">
        @if(json_decode($post->media) != null)
            @if(count(json_decode($post->media)) === 1)
                <!-- post image -->
                <div class="relative w-full h-full" uk-lightbox>
                    @if(App\Classes\FileTypeIdentifier::identify(json_decode($post->media)[0]) == 'video')
                        <a data-type="video" class="relative" href="{{ asset('uploads/posts') . '/' . json_decode($post->media)[0] }}">
                            <video src="{{ asset('uploads/posts') . '/' . json_decode($post->media)[0] }}" class="m:rounded-lg w-full h-full object-cover" alt="video"></video>

                            <div class="absolute top-0 bottom-0 right-0 left-0 flex items-center justify-center">
                                <x-icon name="play-circle" solid class="w-10 h-10" />
                            </div>
                        </a>
                    @else
                        <a href="{{ asset('uploads/posts') . '/' . json_decode($post->media)[0] }}">
                            <img src="{{ asset('uploads/posts') . '/' . json_decode($post->media)[0] }}" class="m:rounded-lg w-full h-full object-cover" alt="image">
                        </a>
                    @endif
                </div>
            @elseif(count(json_decode($post->media)) > 1)
                <!-- slide images -->
                <div class="relative uk-visible-toggle uk-slideshow w-full" tabindex="-1" uk-slideshow="animation: push;finite: true;min-height: 300; max-height: 350">

                    <ul class="uk-slideshow-items" uk-lightbox="" style="min-height: 350px;">
                        @foreach(json_decode($post->media) as $index => $item)
                            <li class="w-full sm:rounded-md" tabindex="-1" wire:key="update-media-{{ $index }}">
                                @if(App\Classes\FileTypeIdentifier::identify($item) == 'video')
                                    <a data-type="video" class="relative" href="{{ asset('uploads/posts') . '/' . $item }}">
                                        <video src="{{ asset('uploads/posts') . '/' . $item }}" class="w-full h-full object-cover inset-0" alt="video"></video>

                                        <div class="absolute top-0 bottom-0 right-0 left-0 flex items-center justify-center">
                                            <x-icon name="play-circle" solid class="w-10 h-10" />
                                        </div>
                                    </a>
                                @else
                                    <a href="{{ asset('uploads/posts') . '/' . $item }}">
                                        <img src="{{ asset('uploads/posts') . '/' . $item }}" class="w-full h-full object-cover inset-0" alt="image">
                                    </a>
                                @endif
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
                    <x-icon name="heart" class="text-rose-500 bg-rose-200 p-0.5 rounded-full" solid/> 
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
        <div class="min-w-7 h-7 max-w-7 max-h-7 rounded-full relative">
            @if(auth()->user()->profilePicture() == null)
                <x-icon name="user" solid class="w-full h-full bg-gray-200 rounded-full border p-.5" />
            @else
                <img src="{{ asset('uploads') . '/' . auth()->user()->profilePicture() }}" class="w-full h-full object-cover rounded-full border">
            @endif

            {{-- <div class="absolute size-2.5 bg-green-500 rounded-full top-0 -right-1"></div> --}}
        </div>

        <input placeholder="Add Comment...." class="w-full resize-none text-sm !bg-transparent px-4 py-2 focus:outline-none focus:!border-transparent focus:!ring-transparent" wire:model="commentContent"></input>

        <button class="bg-gray-100 px-4 py-1.5 h-full rounded-full text-sm font-medium active:scale-95 transition-all w-auto hover:bg-gray-200" wire:loading.attr='disabled' wire:click.prevent='storeComment'>
            <span wire:loading.remove wire:target="storeComment">Send</span>
    
            <div wire:loading wire:target="storeComment"> 
                Sending...
            </div>
        </button>
    </div>
</div>