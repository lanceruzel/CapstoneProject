<?php

namespace App\Livewire\Livestream;

use Livewire\Component;

class LivestreamPostContainer extends Component
{
    public $livestream;

    public function render()
    {
        return view('livewire.Livestream.livestream-post-container');
    }
}
