<?php

namespace App\Livewire\Livestream;

use App\Models\Livestream;
use Livewire\Component;

class LivestreamPostsContainer extends Component
{
    public function render()
    {
        return view('livewire.Livestream.livestream-posts-container', [
            'livestreams' => Livestream::get()
        ]);
    }
}
