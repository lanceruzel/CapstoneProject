<?php

namespace App\Livewire\Notif;

use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class NotificationsContainer extends Component
{
    public $user_id;

    public function mount(){
        $this->user_id = Auth::id();
    }
    
    public function getListeners(){
        return [
            "echo:new-notification.{$this->user_id},NotificationCreated" => '$refresh',
        ];
    }

    public function render()
    {
        return view('livewire.Notif.notifications-container', [
            'notifications' => Notification::where('user_id', Auth::id())->orderBy('id', 'DESC')->get()
        ]);
    }
}
