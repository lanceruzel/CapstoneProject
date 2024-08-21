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
                            @if(json_decode($post->images) != null)
                                <div class="uk-position-relative uk-visible-toggle uk-light w-full" tabindex="-1" uk-slider>
                                    <div class="uk-slider-items uk-child-width-1-2 uk-child-width-1-3@s uk-child-width-1-4@m h-[200px]" uk-lightbox="">
                                        @foreach(json_decode($post->images) as $image)
                                            <a class="mx-2 w-[200px] " href="{{ asset('uploads/posts') . '/' . $image }}">
                                                <img src="{{ asset('uploads/posts') . '/' . $image }}" class="border-2 shadow-md h-full w-full object-cover inset-0" alt="">
                                            </a>
                                        @endforeach
                                    </div>
                                
                                    <x-mini-button flat black class="uk-position-center-left" rounded icon="chevron-left" uk-slider-item="previous" />
                                    <x-mini-button flat black class="uk-position-center-right" rounded icon="chevron-right" uk-slider-item="next" />
                                </div>
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