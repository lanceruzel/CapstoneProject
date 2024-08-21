<?php
    use App\Enums\NotificationType;
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
            {{ App\Classes\CustomDateTimeFormat::formatAgo($notification->created_at) }}
        </div>
    </div>
</div>
