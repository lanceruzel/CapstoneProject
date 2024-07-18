<?php

namespace App\Livewire\Notif;

use Livewire\Component;

class NotificationContainer extends Component
{
    public $notification;

    public function mount($notification){
        $this->notification = $notification;
    }

    public function render()
    {
        return view('livewire.Notif.notification-container', [
            'notification' => $this->notification
        ]);
    }
}
