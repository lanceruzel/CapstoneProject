<?php 
   use App\Enums\UserType;
?>

<div class="flex flex-row items-start gap-3">
   {{-- User Avatar --}}
   <div class="min-w-7 h-7 max-w-7 max-h-7 rounded-full relative">
      @if($comment->user->profilePicture() == null)
          <x-icon name="user" solid class="w-full h-full bg-gray-200 rounded-full border" />
      @else
          <img src="{{ asset('uploads') . '/' . $comment->user->profilePicture() }}" class="w-full h-full object-cover rounded-full border">
      @endif

      @if($comment->user->isOnline())  
         <div class="absolute size-2.5 bg-green-500 rounded-full top-0 -right-1"></div>
      @endif
   </div>

   <div class="flex flex-col w-full">
      <div class="leading-none flex justify-between w-full items-center">
         <a href="{{ route('profile', $comment->user->username) }}" class="hover:no-underline hover:text-gray-700 py-0 font-medium text-sm">{{ $comment->user->name() }}</a>
         <small>{{ App\Classes\CustomDateTimeFormat::formatAgo($comment->created_at) }}</small>
      </div>
      
      <div class="leading-snug text-sm text-gray-600">
        {{ $comment['content'] }}
      </div>
   </div>
</div>