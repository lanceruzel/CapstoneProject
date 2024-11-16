<x-modal-card name="viewTravel" title="{{ $selectedCountry }}" align='center' width='2xl' x-cloak x-on:close="$dispatch('clearViewTravelModal')" blurless wire:ignore.self>  
    @if($posts)
        <div class="w-full flex flex-col items-center justify-center gap-3">
            @if(count($posts) > 0)
                @foreach($posts as $post)
                    <div class="border-b py-4 w-full">
                        <div class="flex gap-2 items-center w-full">
                            <div class="size-10 rounded-full">
                                @if($post->user->profilePicture() == null)
                                    <x-icon name="user" solid class="w-full h-full bg-gray-200 rounded-full p-2 border" />
                                @else
                                    <img src="{{ asset('uploads') . '/' . $post->user->profilePicture() }}" class="w-full h-full object-cover rounded-full border">
                                @endif
                            </div>
        
                            <div class="leading-none">
                                <div class="flex items-center justify-start gap-1">
                                    <a href="{{ route('profile', $post->user->username) }}" class="hover:text-gray-700 hover:no-underline py-0 font-medium">{{ $post->user->name() }}</a>
                                </div>
        
                                <small class="text-xs font-medium text-gray-600">{{ date_format($post->created_at, "M d, Y") }} at {{ date_format($post->created_at, "g:i a") }}</small>
                            </div>
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
                    </div>
                @endforeach
            @else
                <div class="flex flex-col items-center justify-center mt-5">
                    <h1 class="text-2xl font-semibold">No saved posts.</h1>
                    <img class="h-[400px]" src="{{ asset('assets/svg/no-data-3.svg') }}" alt="No data found"/>
                </div>
            @endif
        </div>
    @else
        <div class="flex items-center justify-center w-full">
            <div class="flex items-row items-center justify-center gap-3">
                <x-icon name='arrow-path' class="h-5 w-5 animate-spin"/>

                <span>
                    Fetching Data...
                </span>
            </div>
        </div>
    @endif
</x-modal-card>