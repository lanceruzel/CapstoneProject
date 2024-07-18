<div class="divide-y">
    @if (count($notifications) > 0)
        @foreach ($notifications as $key => $item)
            <livewire:Notif.notification-container :notification="$item" wire:key="{{ $key }}-notif-{{ $item->id }}" />
        @endforeach
    @else
        <div class="flex items-center justify-center py-3">
            No notifications available
        </div>
    @endif
</div>
