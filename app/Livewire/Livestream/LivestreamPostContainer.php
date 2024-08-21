<?php

namespace App\Livewire\Livestream;

use App\Models\Livestream;
use Livewire\Component;

class LivestreamPostContainer extends Component
{
    public $livestream;

    public function mount(Livestream $livestream){
        $this->livestream = $livestream;
    }

    public function render(){
        return view('livewire.Livestream.livestream-post-container');
    }
}
