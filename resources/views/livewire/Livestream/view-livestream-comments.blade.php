<x-modal-card name="viewLivestreamCommentsModal" width="md" title="Comments" align='center' x-cloak x-on:close="$dispatch('clearViewLivestreamCommentsModalData')" blurless wire:ignore.self>
    @if($comments)
        <div class="flex flex-col gap-2 justify-center items-center text-gray-600">
            <livewire:Livestream.livestream-reaction-count :meetingId="$meetingId" noPoll/>

            <div class="p-5 text-sm font-medium space-y-5 overflow-y-auto h-[calc(100vh-25rem)] w-full">
                @foreach ($comments as $comment)
                    @if($comment->user_id == Auth::id())
                        <!-- sent -->
                        <div class="flex flex-col gap-1 items-end">
                            <div class="px-4 py-2 rounded-[20px] max-w-sm bg-gradient-to-tr from-sky-500 to-blue-500 text-white shadow break-words text-wrap hyphens-auto space-y-3 text-sm">
                                {!! html_entity_decode($comment['content']) !!}
                            </div>
                            
                            <small class="pe-2">{{ App\Classes\CustomDateTimeFormat::formatAgo($comment->created_at) }}</small>
                        </div> 
                    @else
                        <!-- received -->
                        <div>
                            <div class="ms-12">
                                <small class="font-semibold">{{ $comment->user->name() }}</small>
                            </div>
        
                            <div class="flex gap-3">
                                <div class="min-w-9 h-9 max-w-9 max-h-9 rounded-full relative">
                                    @if($comment->user->profilePicture() == null)
                                        <x-icon name="user" solid class="w-full h-full bg-gray-200 rounded-full border" />
                                    @else
                                        <img src="{{ asset('uploads') . '/' . $comment->user->profilePicture() }}" class="w-full h-full object-cover rounded-full border">
                                    @endif

                                    @if($comment->user->isOnline())  
                                        <div class="absolute size-3 bg-green-500 rounded-full top-0 -right-1"></div>
                                    @endif
                                </div>
        
                                <div class="px-4 py-2 rounded-[20px] max-w-sm bg-gray-100 break-words !text-wrap hyphens-auto space-y-3 text-sm">
                                    {!! html_entity_decode($comment['content']) !!}
                                </div>
                            </div>
        
                            <div class="!ms-14">
                                <small>{{ App\Classes\CustomDateTimeFormat::formatAgo($comment->created_at) }}</small>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
            
            <x-slot name="footer" class="flex justify-end gap-x-4">
                <x-button flat label="Close" x-on:click="close" />
            </x-slot>
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