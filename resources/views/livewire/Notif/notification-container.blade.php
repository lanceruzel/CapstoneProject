<?php
use App\Enums\NotificationType;

$seconds_ago = (time() - strtotime($notification->created_at));
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
            $dateTimeDisplay = "1 minute ago";
        } else {
            $dateTimeDisplay = "Less than a minute ago";
        }
?>
<div class="w-full p-3 flex items-start justify-start flex-row gap-2 @if($notification->status == 'unread') bg-gray-502 @endif">
    <div>
        <div class="w-8 h-8 flex items-center justify-center rounded-full border">
            @switch($notification->type)
                @case(NotificationType::Order)
                        <x-icon name="truck" class="w-5 h-5" />
                    @break
                @case(NotificationType::Product || NotificationType::ProductRegistration)
                        <x-icon name="shopping-bag" class="w-5 h-5" />
                    @break
                @case(NotificationType::Stock)
                        <x-icon name="arrow-long-down" class="w-5 h-5" />
                    @break
                @case(NotificationType::Invitation)
                        <x-icon name="envelope" class="w-5 h-5" />
                    @break
                @case(NotificationType::Affiliate)
                        <x-icon name="user-group" class="w-5 h-5" />
                    @break
                @case(NotificationType::Appeal)
                        <x-icon name="clipboard" class="w-5 h-5" />
                    @break
                @case(NotificationType::StoreRegistration)
                        <x-icon name="document-text" class="w-5 h-5" />
                    @break
                @case(NotificationType::ReturnRequest)
                        <x-icon name="clipboard-document-list" class="w-5 h-5" />
                    @break
                @default
                        <x-icon name="user" class="w-5 h-5" />
                    @break
            @endswitch
        </div>
    </div>

    <div>
        <div>
            {{ $notification->content }}
        </div>

        <div class="text-sm text-gray-500">
            {{ $dateTimeDisplay }}
        </div>
    </div>
</div>
