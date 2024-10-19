<?php

namespace App\Livewire\Livestream;

use App\Models\Livestream;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

class LivestreamContainer extends Component
{
    use WireUiActions;

    public $name;
    public $role;
    public $meetingId;
    public $location;
    public $playbackUrl;
    public $livestream;

    protected $listeners = [
        'delete-livestream' => 'deleteLivestream',
        'host-leave-notify' => 'leavingRouteMessage',
        'live-ended-notify' => 'liveEndedNotify',
        'live-update' => 'liveUpdateStatus',
        'insert-playback-url' => 'updatePlaybackUrl',
        'end-confirm' => 'leaveConfirmation'
    ];

    public function mount($id){
        $this->livestream = Livestream::find($id);
    }

    public function updatePlaybackUrl($url){
        $this->livestream->playback_url = $url;
        $this->livestream->save();
    }

    public function liveUpdateStatus($status){
        $this->livestream->status = $status;
        $this->livestream->save();
    }

    public function deleteLivestream(){
        if($this->livestream){
            //$livestream->delete();
            $this->livestream->status = 'ended';
            $this->livestream->save();
        }

        $this->dialog()->show([
            'icon' => 'info',
            'title' => 'Info!',
            'description' => 'Livestream has ended.',
        ]);

        return redirect()->route('home');
    }

    public function liveEndedNotify(){
        $this->dialog()->show([
            'icon' => 'info',
            'title' => 'Info!',
            'description' => 'Livestream has ended.',
        ]);

        return redirect()->route('home');
    }

    public function leaveConfirmation(){
        $this->dialog()->confirm([
            'title' => 'Are you Sure?',
            'description' => 'End live?',
            'acceptLabel' => 'Yes, end it',
            'method' => 'leaveMeeting',
            'params' => '',
        ]);
    }

    public function leaveMeeting(){
        $this->dispatch('end-live');
    }

    public function render()
    {
        return view('livewire.Livestream.livestream-container');
    }
}
