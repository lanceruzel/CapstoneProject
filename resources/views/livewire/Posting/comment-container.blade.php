<?php 
   use App\Enums\UserType;
?>

<div class="flex flex-row items-start gap-3">
   {{-- User Avatar --}}
   <div class="min-w-5 h-5 max-w-5 max-h-5 rounded-full">
      @if($comment->user->profilePicture() == null)
          <img src="https://static.everypixel.com/ep-pixabay/0329/8099/0858/84037/3298099085884037069-head.png" alt="" class="bg-gray-100 w-full h-full object-cover rounded-full">
      @else
          <img src="{{ asset('uploads') . '/' . $comment->user->profilePicture() }}" class="w-full h-full object-cover rounded-full">
      @endif
  </div>

   <div class="flex flex-col w-full">
      <div class="leading-none flex justify-between w-full items-center">
         @if ($comment->user->role != App\Enums\UserType::Store)
            <a href="{{ route('home', $comment->user->id) }}" class="hover:no-underline hover:text-gray-700 py-0 font-medium text-sm">{{ $comment->user->userInformation->fullname() }}</a>
         @else
            <a href="{{ route('home', $comment->user->id) }}" class="hover:no-underline hover:text-gray-700 py-0 font-medium text-sm">{{ $comment->user->storeInformation->name }}</a>
         @endif
         
         <small>{{ App\Classes\CustomDateTimeFormat::formatAgo($comment->created_at) }}</small>
      </div>
      
      <div class="leading-snug text-sm text-gray-600">
        {{ $comment['content'] }}
      </div>
   </div>
</div>