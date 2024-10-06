<?php

namespace App\Livewire\Livestream;

use App\Models\Livestream;
use Livewire\Component;

class LivestreamPostsContainer extends Component
{
    public $userID;
    
    public function mount($userID = null){
        $this->userID = $userID != null ? $userID : null;
    }

    public function render()
    {
        // return view('livewire.Livestream.livestream-posts-container', [
        //     'livestreams' => Livestream::orderBy('created_at', 'desc')->get()
        // ]);

        return view('livewire.Livestream.livestream-posts-container', [
            'livestreams' => Livestream::when($this->userID, function ($query, $userID) {
                        return $query->where('user_id', $userID);
                    })
                    ->orderBy('created_at', 'desc')
                    ->get()
        ]);
    }
}
