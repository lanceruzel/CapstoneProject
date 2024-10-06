<?php

namespace App\Livewire\Livestream;

use App\Models\Livestream;
use Livewire\Component;

class LivestreamReactionCount extends Component
{
    public $livestream;
    public $meetingId;
    public $noPoll;

    public function mount($meetingId, $noPoll = false){
        $this->meetingId = $meetingId;
        $this->livestream = Livestream::where('id', $meetingId)->first();
        $this->noPoll = $noPoll ?? false;;
    }

    public function render()
    {
        return view('livewire.Livestream.livestream-reaction-count');
    }
}
