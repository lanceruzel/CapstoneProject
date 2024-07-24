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

    protected $listeners = [
        'delete-livestream' => 'deleteLivestream',
        'notify-viewer' => 'viewerLeaveNotice'
    ];

    public function mount($name, $role, $meetingId){
        $this->name = $name;
        $this->role = $role;
        $this->meetingId = $meetingId;
    }

    public function deleteLivestream(){
        $livestream = Livestream::findOrFail($this->meetingId);

        if($livestream->delete()){
            $this->dialog()->show([
                'icon' => 'info',
                'title' => 'Info!',
                'description' => 'Livestream has already ended.',
            ]);

            return redirect()->route('home');
        }
    }

    public function viewerLeaveNotice(){
        $this->dialog()->show([
            'icon' => 'info',
            'title' => 'Info!',
            'description' => 'Livestream has already ended.',
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
