<?php

namespace App\Livewire\Livestream;

use App\Models\Livestream;
use Livewire\Component;

class LivestreamReactionCount extends Component
{
    public $livestream;
    public $meetingId;

    public function mount($meetingId){
        $this->meetingId = $meetingId;
        $this->livestream = Livestream::where('id', $meetingId)->first();
    }

    public function render()
    {
        return view('livewire.Livestream.livestream-reaction-count');
    }
}
