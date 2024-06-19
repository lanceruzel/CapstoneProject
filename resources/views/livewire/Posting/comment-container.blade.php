<?php 
   use App\Enums\UserType;
   
   $dateTimeNow = new DateTime();
   $interval = $dateTimeNow->diff($comment['created_at']);

   $seconds_ago = (time() - strtotime($comment['created_at']));

   $dateTimeDisplay = '';

   if ($seconds_ago >= 31536000) {
      $dateTimeDisplay = intval($seconds_ago / 31536000) . " years ago";
   } elseif ($seconds_ago >= 2419200) {
      $dateTimeDisplay = intval($seconds_ago / 2419200) . " months ago";
   } elseif ($seconds_ago >= 86400) {
      $dateTimeDisplay = intval($seconds_ago / 86400) . " days ago";
   } elseif ($seconds_ago >= 3600) {
      $dateTimeDisplay = intval($seconds_ago / 3600) . " hours ago";
   } elseif ($seconds_ago >= 120) {
      $dateTimeDisplay = intval($seconds_ago / 60) . " minutes ago";
   } elseif ($seconds_ago >= 60) {
      $dateTimeDisplay = "A minute ago";
   } else {
      $dateTimeDisplay = "Less than a minute ago";
   }
   
?>

<div class="flex flex-row items-start gap-3">
   {{-- User Avatar --}}
   <div class="size-5 bg-red-500 rounded-full p-4"></div>

   <div class="flex flex-col w-full">
      <div class="leading-none flex justify-between w-full items-center">
         @if ($comment->user->role != App\Enums\UserType::Store)
            <a href="{{ route('home', $comment->user->id) }}" class="hover:no-underline hover:text-gray-700 py-0 font-medium text-sm">{{ $comment->user->userInformation->fullname() }}</a>
         @else
            <a href="{{ route('home', $comment->user->id) }}" class="hover:no-underline hover:text-gray-700 py-0 font-medium text-sm">{{ $comment->user->storeInformation->name }}</a>
         @endif
         
         <small>{{ $dateTimeDisplay }}</small>
      </div>
      
      <div class="leading-snug text-sm text-gray-600">
        {{ $comment['content'] }}
      </div>
   </div>
</div>